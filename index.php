<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$base = base_path();
if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base)) ?: '/';
}
// Query-string fallback for hosts without URL rewriting.
if ($path === '/' && !empty($_GET['page'])) {
    $path = '/' . preg_replace('/[^a-z-]/', '', (string) $_GET['page']);
}

/* ------------------------------------------------- contact form submission */
if ($path === '/enquiry' && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    handle_enquiry();
    exit;
}

/* ------------------------------------------------------------- legal pages */
$legal = ['privacy', 'cookies', 'terms', 'accessibility'];
$slug  = trim($path, '/');
if (in_array($slug, $legal, true)) {
    $stmt = Database::pdo()->prepare('SELECT * FROM pages WHERE slug = ?');
    $stmt->execute([$slug]);
    if ($page = $stmt->fetch()) {
        include APP_ROOT . '/templates/page.php';
        exit;
    }
}

/* ---------------------------------------------------------------- homepage */
if ($path !== '/') {
    http_response_code(404);
    $page = ['title' => 'Page not found', 'body' => '<p>The page you were looking for does not exist. <a href="' . e(url('/')) . '">Back to the villa</a>.</p>'];
    include APP_ROOT . '/templates/page.php';
    exit;
}

ensure_session();      // before any output — the CSRF/session cookie must be settable
csrf_token();
$preview = isset($_GET['preview']) && is_admin();
$sections = sections_for_render($preview);
include APP_ROOT . '/templates/layout.php';

/* ------------------------------------------------------------------------ */

function handle_enquiry(): void
{
    ensure_session();
    $wantsJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');

    $fail = function (string $message, int $code = 422) use ($wantsJson): void {
        if ($wantsJson) {
            http_response_code($code);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => $message]);
        } else {
            flash_set('contact_error', $message);
            header('Location: ' . url('/') . '#contact');
        }
        exit;
    };

    // Honeypot + minimum fill time: silently accept bots without storing.
    $started = (int) ($_POST['_started'] ?? 0);
    if (!empty($_POST['website']) || ($started > 0 && time() - $started < 3)) {
        $ok = true;
    } else {
        if (!csrf_check($_POST['_token'] ?? null)) {
            $fail('Your session expired — please try again.', 419);
        }
        $name    = trim((string) ($_POST['name'] ?? ''));
        $email   = trim((string) ($_POST['email'] ?? ''));
        $phone   = trim((string) ($_POST['phone'] ?? ''));
        $topic   = trim((string) ($_POST['topic'] ?? ''));
        $message = trim((string) ($_POST['message'] ?? ''));
        $consent = !empty($_POST['consent']);

        if ($name === '' || mb_strlen($name) > 120) {
            $fail('Please tell us your name.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $fail('Please enter a valid email address.');
        }
        if ($message === '' || mb_strlen($message) > 5000) {
            $fail('Please include a short message.');
        }
        if (!$consent) {
            $fail('Please confirm you are happy for us to reply to your enquiry.');
        }

        Database::pdo()
            ->prepare('INSERT INTO leads (name, email, phone, topic, message, consent) VALUES (?, ?, ?, ?, ?, 1)')
            ->execute([$name, $email, mb_substr($phone, 0, 40), mb_substr($topic, 0, 80), $message]);
        notify_lead_by_email($name, $email, $phone, $topic, $message);
        $ok = true;
    }

    if ($wantsJson) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok, 'message' => 'Thank you — your enquiry has been received. We will be in touch shortly.']);
    } else {
        flash_set('contact_success', 'Thank you — your enquiry has been received. We will be in touch shortly.');
        header('Location: ' . url('/') . '#contact');
    }
}

/**
 * Email the enquiry to the address configured in Settings → Contact form.
 * Leads are always stored in the CMS; email is an additional channel and
 * a delivery failure never breaks the submission.
 */
function notify_lead_by_email(string $name, string $email, string $phone, string $topic, string $message): void
{
    $to = trim(setting('lead_notify_email'));
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return;
    }

    $from = trim(setting('lead_from_email'));
    if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
        $host = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
        $from = 'noreply@' . preg_replace('/:\d+$/', '', $host);
    }

    $business = setting('business_name', 'Website');
    // Strip header-injection characters from values used in headers.
    $cleanName  = preg_replace('/[\r\n<>]/', '', $name);
    $replyTo    = preg_replace('/[\r\n]/', '', $email);

    $subject = sprintf('New enquiry from %s — %s', $cleanName, $business);
    $body = "You have received a new enquiry via the {$business} website.\n\n"
        . "Name:    {$name}\n"
        . "Email:   {$email}\n"
        . ($phone !== '' ? "Phone:   {$phone}\n" : '')
        . ($topic !== '' ? "Topic:   {$topic}\n" : '')
        . "\nMessage:\n{$message}\n\n"
        . "—\nThis enquiry is also stored in the CMS under Admin → Leads.\n";

    $headers = [
        'From: ' . $business . ' <' . $from . '>',
        'Reply-To: ' . $cleanName . ' <' . $replyTo . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'X-Mailer: VillaCMS',
    ];

    try {
        @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
    } catch (Throwable) {
        // Never let mail transport issues break the enquiry flow.
    }
}
