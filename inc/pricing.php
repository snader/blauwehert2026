<?php
/**
 * Wakker Bier - Pricing & Packages Configuration
 */

require_once __DIR__ . '/config.php';

// Global Pricing Parameters
$pricing_config = [
    'min_persons'        => 8,
    'default_persons'    => 10,
    'discount_threshold' => 20,    // Persons 21 and above get discount
    'discount_rate'      => 0.05,  // 5% discount on person 21+
    'vat_rate'           => 0.21,  // 21% VAT
];

// Package details & rates (prices excl. VAT per person)
$packages = [
    'klassiek' => [
        'id'               => 'klassiek',
        'name'             => 'Bierproeverij Klassiek (6 bieren)',
        'short_name'       => 'Klassiek',
        'subtitle'         => '6 speciaalbieren & verhalen',
        'price_per_person' => 27.50,
        'badge'            => 'Populairste Keuze',
        'description'      => 'De perfecte allround proeverij met 6 verschillende speciaalbieren, smaakwiel, bierverhalen en een interactieve quiz.',
        'features'         => [
            '6 verrassende speciaalbieren (van blond tot stout)',
            'Officiële bierproefglazen & plezier inbegrepen',
            'Inclusief presentatie en verhalen',
            'Volledige ontzorging van A tot Z',
            'Ca. 2 tot 2,5 uur gezelligheid & kennis'
        ],
        'is_featured'      => false,
        'cta_text'         => 'Kies Klassiek'
    ],
    'hapjes' => [
        'id'               => 'hapjes',
        'name'             => 'Bier & Hapjes (6 bieren + bites)',
        'short_name'       => 'Bier & Hapjes',
        'subtitle'         => '6 bieren + 6 borrelbites',
        'price_per_person' => 35,50,
        'badge'            => 'Meest Gekozen ⭐',
        'description'      => 'Luxe combinatie van 6 speciaalbieren met 6 zorgvuldig samengestelde bourgondische borrelbites & fingerfood.',
        'features'         => [
            '6 speciaalbieren & 6 bijpassende hapjes',
            'Inclusief alle proefglazen, presentatie en plezier',
            'Uitleg over bier & food combinaties',
            'Glutenvrij en vegetarisch mogelijk',
            'Volledige ontzorging van A tot Z'
        ],
        'is_featured'      => true,
        'cta_text'         => 'Kies Bier & Hapjes'
    ],
    'kaas' => [
        'id'               => 'kaas',
        'name'             => 'Bier & Ambachtelijke Kaas',
        'short_name'       => 'Bier & Kaas',
        'subtitle'         => '6 bieren + 6 kwaliteitskazen',
        'price_per_person' => 36.50,
        'badge'            => 'Voor Fijnproevers',
        'description'      => 'Vergeet wijn en kaas: ontdek hoe speciaalbier de ultieme partner is voor ambachtelijke boeren- en speciaalkazen.',
        'features'         => [
            '6 speciaalbieren + 6 geselecteerde kazen',
            'Inclusief crackers, presentatie en gezelligheid',
            'Officiële proefglazen',
            'Foodpairing & smaakbeleving',
            'Ca. 2,5 uur culinair genieten'
        ],
        'is_featured'      => false,
        'cta_text'         => 'Kies Bier & Kaas'
    ],
    'thema' => [
        'id'               => 'thema',
        'name'             => 'Themaproeverij naar Keuze',
        'short_name'       => 'Themaproeverij',
        'subtitle'         => 'Bijv. Abdij, IPA, Lokaal of Seizoen',
        'price_per_person' => 27.50,
        'badge'            => 'Op Maat',
        'description'      => 'Kies een specifiek thema zoals Trappist & Abdijbieren, Hop & IPA\'s, Nederlandse Microbrouwerijen of Winter/Zomerbieren.',
        'features'         => [
            '6 thematische speciaalbieren',
            'Diepgaande achtergrondverhalen & historie',
            'Ideaal voor bedrijven',
            'In overleg volledig aanpasbaar',
            'Optioneel uit te breiden met hapjes'
        ],
        'is_featured'      => false,
        'cta_text'         => 'Kies Thema'
    ]
];

/**
 * Calculate total price with tiered 5% discount for person 21+
 *
 * @param float $price_per_person Base price per person (excl VAT)
 * @param int $persons Number of persons
 * @return array Calculated pricing details
 */
function calculate_proeverij_total($price_per_person, $persons) {
    global $pricing_config;

    $threshold = $pricing_config['discount_threshold'];
    $discount_rate = $pricing_config['discount_rate'];
    $vat_rate = $pricing_config['vat_rate'];

    if ($persons <= $threshold) {
        $base_persons = $persons;
        $discounted_persons = 0;
        $base_subtotal = $persons * $price_per_person;
        $discounted_subtotal = 0;
        $discount_amount = 0;
    } else {
        $base_persons = $threshold;
        $discounted_persons = $persons - $threshold;
        $base_subtotal = $base_persons * $price_per_person;
        $discounted_unit_price = $price_per_person * (1 - $discount_rate);
        $discounted_subtotal = $discounted_persons * $discounted_unit_price;
        $discount_amount = ($discounted_persons * $price_per_person) * $discount_rate;
    }

    $subtotal = $base_subtotal + $discounted_subtotal;
    $vat = $subtotal * $vat_rate;
    $total = $subtotal + $vat;

    return [
        'persons'            => $persons,
        'base_price'         => $price_per_person,
        'base_persons'       => $base_persons,
        'discounted_persons' => $discounted_persons,
        'discount_amount'    => $discount_amount,
        'has_discount'       => $discounted_persons > 0,
        'subtotal'           => $subtotal,
        'vat'                => $vat,
        'total'              => $total
    ];
}
