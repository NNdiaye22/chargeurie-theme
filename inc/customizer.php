<?php
/**
 * Chargeurie — Customizer options
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function chg_customizer_register( $wp_customize ) {

    // Panel
    $wp_customize->add_panel( 'chg_panel', [
        'title'    => 'Chargeurie — Thème',
        'priority' => 30,
    ] );

    // ---- Section : Identité ----
    $wp_customize->add_section( 'chg_identity', [
        'title' => 'Identité',
        'panel' => 'chg_panel',
    ] );
    $fields_identity = [
        'chg_tagline'       => [ 'default' => 'Câbles & Accessoires de Charge — France', 'label' => 'Sous-titre nav' ],
        'chg_ticker_text'   => [ 'default' => 'Charge Rapide 60 W · USB-C 3A · 5 Coloris · Lanière Téléphone · Câble Bracelet · 4-en-1 · Livraison offerte dès 35 € · Garantie 2 ans ·', 'label' => 'Ticker texte' ],
        'chg_instagram_url' => [ 'default' => '#', 'label' => 'Instagram URL' ],
        'chg_tiktok_url'    => [ 'default' => '#', 'label' => 'TikTok URL' ],
        'chg_linkedin_url'  => [ 'default' => '#', 'label' => 'LinkedIn URL' ],
        'chg_contact_email' => [ 'default' => 'contact@chargeurie.fr', 'label' => 'Email de contact' ],
    ];
    foreach ( $fields_identity as $key => $args ) {
        $wp_customize->add_setting( $key, [ 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $key, [ 'label' => $args['label'], 'section' => 'chg_identity', 'type' => 'text' ] );
    }

    // ---- Section : Hero ----
    $wp_customize->add_section( 'chg_hero', [
        'title' => 'Page d\'accueil — Hero',
        'panel' => 'chg_panel',
    ] );
    $fields_hero = [
        'chg_hero_line1'    => [ 'default' => 'PORTEZ',   'label' => 'Ligne 1 (plein)' ],
        'chg_hero_line2'    => [ 'default' => 'VOTRE',    'label' => 'Ligne 2 (outline)' ],
        'chg_hero_line3'    => [ 'default' => 'CHARGE',   'label' => 'Ligne 3 (plein)' ],
        'chg_hero_subtitle' => [ 'default' => 'Des câbles pensés pour être portés au quotidien — autour du cou, au poignet, en porte-clé.', 'label' => 'Sous-titre hero' ],
        'chg_hero_cta'      => [ 'default' => 'Découvrir la gamme', 'label' => 'CTA hero' ],
    ];
    foreach ( $fields_hero as $key => $args ) {
        $wp_customize->add_setting( $key, [ 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $key, [ 'label' => $args['label'], 'section' => 'chg_hero', 'type' => 'text' ] );
    }

    // ---- Bannière hero : images desktop + mobile ----
    $wp_customize->add_setting( 'chg_hero_img_desktop', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'chg_hero_img_desktop', [
        'label'       => '🖥 Image Hero — Desktop (recommandé : 1920×1080)',
        'description' => 'Affichée sur écrans > 768px.',
        'section'     => 'chg_hero',
        'mime_type'   => 'image',
    ] ) );

    $wp_customize->add_setting( 'chg_hero_img_mobile', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'chg_hero_img_mobile', [
        'label'       => '📱 Image Hero — Mobile (recommandé : 750×1334)',
        'description' => 'Affichée sur écrans ≤ 768px. Si vide, l\'image desktop sera utilisée.',
        'section'     => 'chg_hero',
        'mime_type'   => 'image',
    ] ) );

    $wp_customize->add_setting( 'chg_hero_overlay', [
        'default'           => '0.55',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'chg_hero_overlay', [
        'label'       => 'Opacité overlay sombre (0 = transparent, 1 = noir)',
        'description' => 'Assombrit la bannière pour garder le texte lisible.',
        'section'     => 'chg_hero',
        'type'        => 'range',
        'input_attrs' => [ 'min' => '0', 'max' => '1', 'step' => '0.05' ],
    ] );

    // ---- Section : Manifeste ----
    $wp_customize->add_section( 'chg_manifesto', [
        'title' => 'Page d\'accueil — Manifeste',
        'panel' => 'chg_panel',
    ] );
    $wp_customize->add_setting( 'chg_manifesto_text', [ 'default' => 'Conçu pour être porté, pas rangé.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'chg_manifesto_text', [ 'label' => 'Phrase manifeste', 'section' => 'chg_manifesto', 'type' => 'text' ] );
    $wp_customize->add_setting( 'chg_manifesto_sub', [ 'default' => 'La technologie la plus utile est celle que vous avez toujours sur vous. Chargeurie conçoit des accessoires qui disparaissent dans votre quotidien — jusqu\'au moment où vous en avez besoin.', 'sanitize_callback' => 'sanitize_textarea_field' ] );
    $wp_customize->add_control( 'chg_manifesto_sub', [ 'label' => 'Sous-texte manifeste', 'section' => 'chg_manifesto', 'type' => 'textarea' ] );

    // ---- Section : Couleurs ----
    $wp_customize->add_section( 'chg_colors', [ 'title' => 'Couleurs', 'panel' => 'chg_panel' ] );
    $wp_customize->add_setting( 'chg_color_blue', [ 'default' => '#0071e3', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'chg_color_blue', [ 'label' => 'Couleur accent (bleu)', 'section' => 'chg_colors' ] ) );

    // ---- Section : Stats ----
    $wp_customize->add_section( 'chg_stats', [ 'title' => 'Page d\'accueil — Stats', 'panel' => 'chg_panel' ] );
    $stat_defaults = [
        1 => [ 'num' => '60', 'unit' => 'Puissance',  'suffix' => 'W',   'desc' => 'Charge rapide sur l\'ensemble de la gamme' ],
        2 => [ 'num' => '5',  'unit' => 'Coloris',    'suffix' => '',    'desc' => 'Chaque produit décliné en plusieurs teintes' ],
        3 => [ 'num' => '4',  'unit' => 'En 1',       'suffix' => '',    'desc' => 'Compatibilité universelle avec un seul câble' ],
        4 => [ 'num' => '2',  'unit' => 'Garantie',   'suffix' => 'ans', 'desc' => 'Garantie constructeur sur tous les produits' ],
    ];
    for ( $i = 1; $i <= 4; $i++ ) {
        foreach ( [ 'num', 'unit', 'suffix', 'desc' ] as $f ) {
            $wp_customize->add_setting( "chg_stat_{$i}_{$f}", [ 'default' => $stat_defaults[$i][$f], 'sanitize_callback' => 'sanitize_text_field' ] );
            $wp_customize->add_control( "chg_stat_{$i}_{$f}", [ 'label' => "Stat $i — " . ucfirst($f), 'section' => 'chg_stats', 'type' => 'text' ] );
        }
    }

    // ---- Section : Formulaires ----
    $wp_customize->add_section( 'chg_forms', [ 'title' => 'Formulaires', 'panel' => 'chg_panel' ] );
    $wp_customize->add_setting( 'chg_cf7_contact_id',    [ 'default' => '', 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( 'chg_cf7_contact_id',    [ 'label' => 'CF7 — ID formulaire contact', 'section' => 'chg_forms', 'type' => 'number' ] );
    $wp_customize->add_setting( 'chg_wpforms_contact_id',[ 'default' => '', 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( 'chg_wpforms_contact_id',[ 'label' => 'WPForms — ID formulaire contact', 'section' => 'chg_forms', 'type' => 'number' ] );
    $wp_customize->add_setting( 'chg_mc4wp_form_id',    [ 'default' => '', 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( 'chg_mc4wp_form_id',    [ 'label' => 'Mailchimp MC4WP — ID formulaire newsletter', 'section' => 'chg_forms', 'type' => 'number' ] );
}
add_action( 'customize_register', 'chg_customizer_register' );

// Live preview : custom property CSS
add_action( 'wp_head', function() {
    $blue = get_theme_mod( 'chg_color_blue', '#0071e3' );
    echo '<style id="chg-live-color">:root{--blue:' . esc_attr( $blue ) . ';}</style>';
} );
