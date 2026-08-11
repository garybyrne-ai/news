<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

/**
 * Editable-field schemas per section type. Simple values render as
 * text/textarea inputs; list content renders as a JSON editor with
 * the expected shape shown alongside.
 */
const SECTION_FIELDS = [
    'hero' => [
        'eyebrow'           => ['Eyebrow (category / location)', 'text'],
        'headline'          => ['Headline (one line per row, 2–3 rows)', 'textarea'],
        'intro'             => ['Supporting copy', 'textarea'],
        'cta_primary'       => ['Primary CTA label', 'text'],
        'cta_primary_url'   => ['Primary CTA URL', 'text'],
        'cta_secondary'     => ['Secondary CTA label', 'text'],
        'cta_secondary_url' => ['Secondary CTA URL', 'text'],
        'scroll_cue'        => ['Scroll cue text', 'text'],
        'cards'             => ['Floating cards', 'json', '[{"title":"…","text":"…"}]'],
    ],
    'intro' => [
        'statement' => ['Large statement', 'textarea'],
        'text'      => ['Supporting paragraph', 'textarea'],
        'metrics'   => ['Trust metrics', 'json', '[{"value":"3","label":"Bedrooms"}]'],
    ],
    'services' => [
        'eyebrow' => ['Eyebrow', 'text'],
        'heading' => ['Heading', 'text'],
        'items'   => ['Service items', 'json', '[{"title":"…","text":"…","image":"","visual":"pool|terrace|bedrooms|kitchen|bbq|essentials"}]'],
    ],
    'why' => [
        'heading' => ['Heading', 'text'],
        'items'   => ['Feature statements', 'json', '[{"title":"…","text":"…"}]'],
    ],
    'split' => [
        'eyebrow'  => ['Eyebrow', 'text'],
        'heading'  => ['Heading', 'text'],
        'text'     => ['Description', 'textarea'],
        'image'    => ['Image URL (blank = stylised placeholder)', 'text'],
        'features' => ['Feature list', 'json', '["Feature one","Feature two"]'],
        'cta'      => ['CTA label', 'text'],
        'cta_url'  => ['CTA URL', 'text'],
    ],
    'process' => [
        'eyebrow' => ['Eyebrow', 'text'],
        'heading' => ['Heading', 'text'],
        'steps'   => ['Steps', 'json', '[{"title":"…","text":"…"}]'],
    ],
    'areas' => [
        'eyebrow' => ['Eyebrow', 'text'],
        'heading' => ['Heading', 'text'],
        'text'    => ['Description', 'textarea'],
        'places'  => ['Places & distances', 'json', '[{"name":"…","distance":"1.3 km"}]'],
        'cta'     => ['CTA label', 'text'],
        'cta_url' => ['CTA URL', 'text'],
    ],
    'reviews' => [
        'eyebrow' => ['Eyebrow', 'text'],
        'heading' => ['Heading', 'text'],
        'items'   => ['Reviews (real reviews only — never fabricate)', 'json', '[{"quote":"…","name":"…","location":"…","rating":"5","source":"Booking.com"}]'],
    ],
    'faq' => [
        'eyebrow' => ['Eyebrow', 'text'],
        'heading' => ['Heading', 'text'],
        'text'    => ['Description', 'textarea'],
        'cta'     => ['CTA label', 'text'],
        'cta_url' => ['CTA URL', 'text'],
        'items'   => ['Questions', 'json', '[{"q":"…","a":"…"}]'],
    ],
    'cta' => [
        'heading'         => ['Heading', 'text'],
        'text'            => ['Supporting sentence', 'textarea'],
        'cta_primary'     => ['Primary CTA label', 'text'],
        'cta_primary_url' => ['Primary CTA URL', 'text'],
        'cta_secondary'   => ['Secondary CTA label (calls the phone number)', 'text'],
    ],
    'contact' => [
        'eyebrow' => ['Eyebrow', 'text'],
        'heading' => ['Heading', 'text'],
        'text'    => ['Description', 'textarea'],
        'topics'  => ['Enquiry topics', 'json', '["Availability","Pricing"]'],
    ],
];

const SECTION_BACKGROUNDS = ['default', 'light', 'surface', 'dark', 'gradient', 'accent', 'transparent'];

function admin_header(string $title): void
{
    $name = setting('business_name', 'CMS');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
        . '<meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<meta name="robots" content="noindex, nofollow">'
        . '<title>' . e($title) . ' — ' . e($name) . ' Admin</title>'
        . '<link rel="icon" href="' . e(url('assets/img/favicon.svg')) . '" type="image/svg+xml">'
        . '<link rel="stylesheet" href="' . e(asset('assets/css/admin.css')) . '"></head><body>';
    if (is_admin()) {
        echo '<header class="topbar"><div class="topbar__inner">'
            . '<a class="topbar__brand" href="' . e(url('/admin/')) . '">' . e($name) . ' <span>Admin</span></a>'
            . '<nav class="topbar__nav">'
            . '<a href="' . e(url('/admin/')) . '">Sections</a>'
            . '<a href="' . e(url('/admin/settings.php')) . '">Settings</a>'
            . '<a href="' . e(url('/admin/leads.php')) . '">Leads</a>'
            . '<a href="' . e(url('/admin/preview.php')) . '">Preview</a>'
            . '<a href="' . e(url('/')) . '" target="_blank" rel="noopener">View site ↗</a>'
            . '<a class="topbar__logout" href="' . e(url('/admin/logout.php')) . '">Log out</a>'
            . '</nav></div></header>';
    }
    echo '<main class="admin-main">';
    foreach (['ok' => 'notice--ok', 'err' => 'notice--err'] as $key => $class) {
        if ($message = flash_get('admin_' . $key)) {
            echo '<p class="notice ' . $class . '" role="status">' . e($message) . '</p>';
        }
    }
}

function admin_footer(): void
{
    echo '</main></body></html>';
}

function admin_require_post_token(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !csrf_check($_POST['_token'] ?? null)) {
        flash_set('admin_err', 'Security token expired — please try again.');
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('/admin/')));
        exit;
    }
}
