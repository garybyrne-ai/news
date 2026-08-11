<?php

declare(strict_types=1);

/**
 * JSON-LD structured data built from CMS content: a VacationRental
 * (LocalBusiness fallback data included) and FAQPage when FAQs exist.
 */
function schema_org_json(): string
{
    if (setting('schema_enabled', '1') !== '1') {
        return '';
    }

    $origin  = site_origin();
    $name    = setting('business_name');
    $graph   = [];

    $lodging = [
        '@type'       => 'VacationRental',
        '@id'         => $origin . url('/') . '#business',
        'name'        => $name,
        'description' => setting('seo_description'),
        'url'         => $origin . url('/'),
        'address'     => [
            '@type'           => 'PostalAddress',
            'addressLocality' => setting('locality'),
            'addressRegion'   => setting('region'),
            'addressCountry'  => setting('country', 'ES'),
        ],
    ];
    if (setting('phone') !== '') {
        $lodging['telephone'] = setting('phone');
    }
    if (setting('email') !== '') {
        $lodging['email'] = setting('email');
    }
    $graph[] = $lodging;

    if ($faq = section_by_type('faq')) {
        $items = section_content($faq)['items'] ?? [];
        $qa = [];
        foreach ($items as $item) {
            if (empty($item['q']) || empty($item['a'])) {
                continue;
            }
            $qa[] = [
                '@type'          => 'Question',
                'name'           => $item['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
            ];
        }
        if ($qa) {
            $graph[] = ['@type' => 'FAQPage', 'mainEntity' => $qa];
        }
    }

    return json_encode(
        ['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
}
