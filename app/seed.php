<?php

declare(strict_types=1);

/**
 * Canonical default content. Used to seed a fresh database AND by the
 * content upgrader (app/upgrade.php), which merges newly introduced
 * keys/sections into existing databases without touching user edits.
 *
 * Content uses only verified Villa Andie facts (listing data: Puerto
 * del Carmen, Tías, Lanzarote). Anything not verifiable is left blank
 * or marked as an editable placeholder — never presented as fact.
 */
function seed_default_settings(): array
{
    return [
        'business_name'     => 'Villa Andie',
        'business_tagline'  => 'Private pool villa in Puerto del Carmen, Lanzarote',
        'business_category' => 'Holiday Villa',
        'phone'             => '',
        'email'             => '',
        'address'           => 'Puerto del Carmen, Tías, Lanzarote, Canary Islands, Spain',
        'locality'          => 'Puerto del Carmen',
        'region'            => 'Lanzarote, Canary Islands',
        'country'           => 'ES',
        'status_text'       => 'Enquiries open',
        'geo_label'         => '28.92° N · 13.66° W',
        'hours'             => 'Enquiries answered daily',
        'instagram'         => '',
        'facebook'          => '',
        // Contact form notifications: leads are always stored in the CMS;
        // when lead_notify_email is set they are also emailed there.
        'lead_notify_email' => '',
        'lead_from_email'   => '',
        'seo_title'         => 'Villa Andie — Private Pool Villa in Puerto del Carmen, Lanzarote',
        'seo_description'   => 'Villa Andie is a 3-bedroom holiday villa with private pool, sea-view terrace and free parking in Puerto del Carmen, Lanzarote — 1.3 km from Puerto del Carmen Beach and 6 km from Lanzarote Airport.',
        'og_image'          => '',
        'schema_enabled'    => '1',
        'faq_single_open'   => '1',
        // Theme (defaults live in CSS; set here to override)
        'color_bg'          => '',
        'color_surface'     => '',
        'color_text'        => '',
        'color_muted'       => '',
        'color_primary'     => '',
        'color_secondary'   => '',
        'color_accent'      => '',
        'color_border'      => '',
        'color_success'     => '',
    ];
}

function seed_default_sections(): array
{
    return [
        [
            'type' => 'hero', 'label' => 'Hero', 'anchor' => 'home', 'background' => 'dark',
            'content' => [
                'eyebrow'        => 'Private villa — Puerto del Carmen, Lanzarote',
                'headline'       => "Your own villa.\nYour own pool.\nAll of Lanzarote around you.",
                'intro'          => 'Villa Andie is a three-bedroom holiday villa with a private pool, sea-view terrace and free parking — a short stroll from Puerto del Carmen Beach and just 6 km from Lanzarote Airport.',
                'cta_primary'    => 'Check availability',
                'cta_primary_url' => '#contact',
                'cta_secondary'  => 'Explore the villa',
                'cta_secondary_url' => '#about',
                'image'          => 'assets/img/villa/hero.jpg',
                'image_alt'      => 'Villa Andie — whitewashed Lanzarote villa with private pool and sun loungers',
                'cards'          => [
                    ['title' => 'Private pool',        'text' => 'Yours alone, all stay long'],
                    ['title' => '3 bedrooms · 2 baths', 'text' => 'Linen and towels included'],
                    ['title' => 'Sea-view terrace',    'text' => 'Patio with outdoor furniture'],
                ],
                'scroll_cue' => 'Scroll to explore',
            ],
        ],
        [
            'type' => 'intro', 'label' => 'Trust / Intro', 'anchor' => 'about', 'background' => 'light',
            'content' => [
                'statement' => 'A private villa holiday without the usual hassle.',
                'text'      => 'No shared corridors, no crowded pools. Villa Andie gives you a whole house in the heart of Puerto del Carmen — with a private entrance, free WiFi, free parking and facilities for guests with disabilities.',
                'images'    => [
                    ['src' => 'assets/img/villa/intro-entrance.jpg', 'alt' => 'Private entrance of the villa framed by palms'],
                    ['src' => 'assets/img/villa/intro-terrace.jpg',  'alt' => 'Upper terrace of the villa with views over Puerto del Carmen'],
                    ['src' => 'assets/img/villa/intro-pool.jpg',     'alt' => 'Sun lounger with towels beside the private pool'],
                ],
                'metrics'   => [
                    ['value' => '3',      'label' => 'Bedrooms'],
                    ['value' => '2',      'label' => 'Bathrooms'],
                    ['value' => '1.3 km', 'label' => 'To Puerto del Carmen Beach'],
                    ['value' => '6 km',   'label' => 'From Lanzarote Airport'],
                ],
            ],
        ],
        [
            'type' => 'services', 'label' => 'The Villa (Services)', 'anchor' => 'services', 'background' => 'light',
            'content' => [
                'eyebrow' => 'The villa',
                'heading' => 'Everything a good week needs',
                'items'   => [
                    ['title' => 'Private pool',      'text' => 'A pool that is entirely yours — no timetables, no towels on loungers at dawn.', 'image' => 'assets/img/villa/pool.jpg',    'visual' => 'pool'],
                    ['title' => 'Sea-view terrace',  'text' => 'A furnished terrace and patio looking over the town to the Atlantic.',           'image' => 'assets/img/villa/terrace.jpg', 'visual' => 'terrace'],
                    ['title' => 'Three bedrooms',    'text' => 'Sleeps the whole family or two couples, with two bathrooms and linen included.', 'image' => 'assets/img/villa/bedroom.jpg', 'visual' => 'bedrooms'],
                    ['title' => 'Full kitchen',      'text' => 'A fully equipped kitchen for slow breakfasts and proper holiday cooking.',       'image' => 'assets/img/villa/kitchen.jpg', 'visual' => 'kitchen'],
                    ['title' => 'Barbecue & patio',  'text' => 'Barbecue facilities and outdoor furniture for warm Canarian evenings.',          'image' => 'assets/img/villa/bbq.jpg',     'visual' => 'bbq'],
                    ['title' => 'The essentials',    'text' => 'Free WiFi, free private parking, satellite TV and facilities for disabled guests.', 'image' => 'assets/img/villa/living.jpg', 'visual' => 'essentials'],
                ],
            ],
        ],
        [
            'type' => 'why', 'label' => 'Why Choose Us', 'anchor' => 'why-us', 'background' => 'dark',
            'content' => [
                'heading'  => 'Why Villa Andie',
                'bg_image' => 'assets/img/villa/why-bg.jpg',
                'items'    => [
                    ['title' => 'Genuinely private',        'text' => 'Private pool, private entrance, private parking — the villa is yours and only yours.'],
                    ['title' => 'Sea and mountain views',   'text' => 'A balcony with mountain views and a terrace facing the sea.'],
                    ['title' => 'Walk to the beach',        'text' => 'Puerto del Carmen Beach is 1.3 km away; Playa Chica 1.4 km.'],
                    ['title' => 'Accessible and equipped',  'text' => 'Facilities for disabled guests, plus everything from bed linen to a full kitchen.'],
                ],
            ],
        ],
        [
            'type' => 'split', 'label' => 'Split Feature', 'anchor' => 'living', 'background' => 'light',
            'content' => [
                'eyebrow'  => 'Life at the villa',
                'heading'  => 'Made for slow mornings and long evenings',
                'text'     => 'Cook when you feel like it, swim when the mood takes you, and let the days set their own pace. The villa comes ready for real living, not just sleeping.',
                'image'    => 'assets/img/villa/slow-mornings.jpg',
                'image_label' => 'The patio — outdoor dining',
                'features' => [
                    'Fully equipped kitchen',
                    'TV with satellite channels',
                    'Bed linen and towels provided',
                    'Outdoor furniture on the patio',
                    'Private entrance',
                    'Barbecue facilities',
                ],
                'cta'     => 'Ask about your dates',
                'cta_url' => '#contact',
            ],
        ],
        [
            'type' => 'gallery', 'label' => 'Photo Gallery', 'anchor' => 'gallery', 'background' => 'surface',
            'content' => [
                'eyebrow' => 'The spaces',
                'heading' => 'A look around',
                'text'    => 'Every corner of the villa, exactly as you will find it.',
                'items'   => [
                    ['src' => 'assets/img/villa/gallery-pool-wide.jpg', 'caption' => 'The private pool',        'alt' => 'Private pool of Villa Andie in front of the whitewashed house'],
                    ['src' => 'assets/img/villa/gallery-exterior.jpg',  'caption' => 'The villa',               'alt' => 'Two-storey exterior of Villa Andie with balcony'],
                    ['src' => 'assets/img/villa/gallery-terrace.jpg',   'caption' => 'Upper terrace',           'alt' => 'Covered upper terrace with rooftop views'],
                    ['src' => 'assets/img/villa/gallery-living.jpg',    'caption' => 'Living room',             'alt' => 'Bright living room with sofas'],
                    ['src' => 'assets/img/villa/gallery-dining.jpg',    'caption' => 'Dining',                  'alt' => 'Dining table set with glasses and fresh pineapple'],
                    ['src' => 'assets/img/villa/gallery-kitchen.jpg',   'caption' => 'The kitchen',             'alt' => 'Fully equipped kitchen with dining area'],
                    ['src' => 'assets/img/villa/gallery-bedroom.jpg',   'caption' => 'Master bedroom',          'alt' => 'Double bedroom with made-up bed'],
                    ['src' => 'assets/img/villa/gallery-bathroom.jpg',  'caption' => 'Bathroom',                'alt' => 'Bathroom with bathtub'],
                    ['src' => 'assets/img/villa/gallery-entrance.jpg',  'caption' => 'Private entrance',        'alt' => 'Entrance patio with wooden door and palm tree'],
                    ['src' => 'assets/img/villa/gallery-lounger.jpg',   'caption' => 'Poolside',                'alt' => 'Sun lounger with a fresh towel beside the pool'],
                ],
            ],
        ],
        [
            'type' => 'process', 'label' => 'Process', 'anchor' => 'process', 'background' => 'light',
            'content' => [
                'eyebrow' => 'How it works',
                'heading' => 'From enquiry to arrival',
                'steps'   => [
                    ['title' => 'Enquire',  'text' => 'Send your dates and party size through the form below.'],
                    ['title' => 'Confirm',  'text' => 'We reply with availability and everything you need to decide.'],
                    ['title' => 'Prepare',  'text' => 'You receive arrival details, directions and local tips.'],
                    ['title' => 'Unwind',   'text' => 'Land, drive 6 km from the airport, and the pool is waiting.'],
                ],
            ],
        ],
        [
            'type' => 'areas', 'label' => 'Location / Area', 'anchor' => 'areas', 'background' => 'dark',
            'content' => [
                'eyebrow'   => 'The location',
                'heading'   => "Proudly placed in Puerto del Carmen",
                'text'      => 'Villa Andie sits in Puerto del Carmen on Lanzarote\'s sunny south-east coast — beaches, restaurants and the old harbour all within easy reach.',
                'image'     => 'assets/img/villa/areas-villa.jpg',
                'image_label' => 'Villa Andie, Puerto del Carmen',
                'places'    => [
                    ['name' => 'Puerto del Carmen Beach', 'distance' => '1.3 km'],
                    ['name' => 'Playa Chica',             'distance' => '1.4 km'],
                    ['name' => 'Lanzarote Golf Resort',   'distance' => '2.5 km'],
                    ['name' => 'Rancho Texas Park',       'distance' => '2.7 km'],
                    ['name' => 'Playa de los Pocillos',   'distance' => '2.8 km'],
                    ['name' => 'Lanzarote Airport',       'distance' => '6 km'],
                ],
                'cta'     => 'Plan your stay',
                'cta_url' => '#contact',
            ],
        ],
        [
            // Hidden by default: no verified guest reviews are available.
            // Replace the placeholder with real reviews, then enable.
            'type' => 'reviews', 'label' => 'Guest Reviews', 'anchor' => 'reviews', 'background' => 'light',
            'enabled' => 0,
            'content' => [
                'eyebrow' => 'Guest words',
                'heading' => 'What guests say',
                'items'   => [
                    [
                        'quote'    => '[Sample placeholder — replace with a real guest review before enabling this section.]',
                        'name'     => 'Guest name',
                        'location' => 'Guest location',
                        'rating'   => '',
                        'source'   => '',
                    ],
                ],
            ],
        ],
        [
            'type' => 'faq', 'label' => 'FAQ', 'anchor' => 'faq', 'background' => 'light',
            'content' => [
                'eyebrow' => 'Good to know',
                'heading' => 'Questions, answered',
                'text'    => 'The practical details guests ask about most. Anything else — just send us a message.',
                'cta'     => 'Ask a question',
                'cta_url' => '#contact',
                'items'   => [
                    ['q' => 'Is the pool really private?', 'a' => 'Yes. The pool belongs to the villa alone and is reserved entirely for your party during your stay.'],
                    ['q' => 'How far is the beach?', 'a' => 'Puerto del Carmen Beach is about 1.3 km away and Playa Chica about 1.4 km — both an easy walk or a couple of minutes by car. Playa de los Pocillos is 2.8 km away.'],
                    ['q' => 'Is parking included?', 'a' => 'Yes — free private parking is available at the villa, and the drive from Lanzarote Airport is only about 6 km.'],
                    ['q' => 'What is provided inside the villa?', 'a' => 'Three bedrooms, two bathrooms, bed linen and towels, a fully equipped kitchen, a TV with satellite channels, and a terrace and patio with outdoor furniture.'],
                    ['q' => 'Is the villa suitable for guests with disabilities?', 'a' => 'The property offers facilities for disabled guests. Tell us about your needs when you enquire and we will confirm the details for your stay.'],
                    ['q' => 'Is WiFi available?', 'a' => 'Yes, free WiFi is available throughout the villa.'],
                ],
            ],
        ],
        [
            'type' => 'cta', 'label' => 'Final CTA', 'anchor' => 'book', 'background' => 'gradient',
            'content' => [
                'heading'       => 'Ready for Lanzarote?',
                'text'          => 'Tell us your dates and we\'ll take it from there.',
                'image'         => 'assets/img/villa/cta-bg.jpg',
                'cta_primary'   => 'Contact us',
                'cta_primary_url' => '#contact',
                'cta_secondary' => 'Call now',
            ],
        ],
        [
            'type' => 'contact', 'label' => 'Contact', 'anchor' => 'contact', 'background' => 'light',
            'content' => [
                'eyebrow' => 'Contact',
                'heading' => 'Start planning your stay',
                'text'    => 'Send an enquiry and we\'ll come back to you with availability and answers.',
                'image'   => 'assets/img/villa/contact-welcome.jpg',
                'topics'  => ['Availability', 'Pricing', 'Accessibility', 'General question'],
            ],
        ],
    ];
}

function seed_database(PDO $pdo): void
{
    $settings = seed_default_settings();
    // Change this password immediately after first login.
    $settings['admin_password_hash'] = password_hash('change-me-now', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT OR IGNORE INTO settings (key, value) VALUES (?, ?)');
    foreach ($settings as $key => $value) {
        $stmt->execute([$key, $value]);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO sections (type, label, anchor, enabled, sort, background, css_class, content_draft, content_published)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    foreach (seed_default_sections() as $i => $s) {
        $json = json_encode($s['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $stmt->execute([
            $s['type'], $s['label'], $s['anchor'],
            $s['enabled'] ?? 1, ($i + 1) * 10,
            $s['background'], '', $json, $json,
        ]);
    }

    $pages = [
        'privacy'       => ['Privacy Policy', "<p>This website collects the personal details you submit through the contact form (name, email, phone and message) solely to respond to your enquiry. Data is stored securely and never sold or shared with third parties for marketing.</p><p>To request a copy or deletion of your data, contact us using the details on the homepage.</p>"],
        'cookies'       => ['Cookie Policy', "<p>This website uses only essential cookies: a session cookie required for the contact form's security token and the administration area. No analytics, advertising or third-party tracking cookies are set.</p>"],
        'terms'         => ['Terms of Use', "<p>The content of this website is provided for general information about the property. Availability, facilities and distances are indicative and confirmed at the time of booking. All bookings are subject to the terms agreed during the enquiry process.</p>"],
        'accessibility' => ['Accessibility Statement', "<p>We aim for this website to meet WCAG 2.2 AA. It supports keyboard navigation, visible focus states, reduced-motion preferences and semantic structure. The villa itself offers facilities for disabled guests — contact us to discuss specific requirements.</p><p>If you encounter an accessibility barrier on this site, please let us know via the contact form.</p>"],
    ];
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO pages (slug, title, body) VALUES (?, ?, ?)');
    foreach ($pages as $slug => [$title, $body]) {
        $stmt->execute([$slug, $title, $body]);
    }
}
