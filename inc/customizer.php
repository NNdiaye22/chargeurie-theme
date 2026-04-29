<?php
/**
 * Chargeurie — Customizer
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function chg_customizer_register( $wp_customize ) {
    $wp_customize->add_panel( 'chg_panel', [ 'title' => 'Chargeurie — Thème', 'priority' => 30 ] );

    // Identité
    $wp_customize->add_section( 'chg_identity', [ 'title' => 'Identité', 'panel' => 'chg_panel' ] );
    foreach ( [
        'chg_tagline'       => [ 'default' => 'Câbles & Accessoires de Charge — France', 'label' => 'Sous-titre nav' ],
        'chg_ticker_text'   => [ 'default' => 'Charge Rapide 60 W · USB-C 3A · 5 Coloris · Lanière Téléphone · Câble Bracelet · 4-en-1 · Livraison offerte dès 35 € · Garantie 2 ans ·', 'label' => 'Ticker texte' ],
        'chg_instagram_url' => [ 'default' => '#', 'label' => 'Instagram URL' ],
        'chg_tiktok_url'    => [ 'default' => '#', 'label' => 'TikTok URL' ],
        'chg_linkedin_url'  => [ 'default' => '#', 'label' => 'LinkedIn URL' ],
    ] as $key => $args ) {
        $wp_customize->add_setting( $key, [ 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $key, [ 'label' => $args['label'], 'section' => 'chg_identity', 'type' => 'text' ] );
    }

    // Hero
    $wp_customize->add_section( 'chg_hero', [ 'title' => 'Hero', 'panel' => 'chg_panel' ] );
    foreach ( [
        'chg_hero_line1'    => [ 'default' => 'PORTEZ',   'label' => 'Ligne 1' ],
        'chg_hero_line2'    => [ 'default' => 'VOTRE',    'label' => 'Ligne 2 (outline)' ],
        'chg_hero_line3'    => [ 'default' => 'CHARGE',   'label' => 'Ligne 3' ],
        'chg_hero_subtitle' => [ 'default' => 'Des câbles pensés pour être portés au quotidien.', 'label' => 'Sous-titre' ],
        'chg_hero_cta'      => [ 'default' => 'Découvrir la gamme', 'label' => 'Texte CTA' ],
    ] as $key => $args ) {
        $wp_customize->add_setting( $key, [ 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $key, [ 'label' => $args['label'], 'section' => 'chg_hero', 'type' => 'text' ] );
    }

    // Manifeste
    $wp_customize->add_section( 'chg_manifesto', [ 'title' => 'Manifeste', 'panel' => 'chg_panel' ] );
    $wp_customize->add_setting( 'chg_manifesto_text', [ 'default' => 'Conçu pour être porté, pas rangé.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'chg_manifesto_text', [ 'label' => 'Phrase manifeste', 'section' => 'chg_manifesto', 'type' => 'text' ] );
    $wp_customize->add_setting( 'chg_manifesto_sub', [ 'default' => 'La technologie la plus utile est celle que vous avez toujours sur vous.', 'sanitize_callback' => 'sanitize_textarea_field' ] );
    $wp_customize->add_control( 'chg_manifesto_sub', [ 'label' => 'Sous-texte manifeste', 'section' => 'chg_manifesto', 'type' => 'textarea' ] );

    // Couleurs
    $wp_customize->add_section( 'chg_colors', [ 'title' => 'Couleurs', 'panel' => 'chg_panel' ] );
    $wp_customize->add_setting( 'chg_color_blue', [ 'default' => '#0071e3', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'chg_color_blue', [ 'label' => 'Couleur accent', 'section' => 'chg_colors' ] ) );

    // Stats
    $wp_customize->add_section( 'chg_stats', [ 'title' => 'Stats', 'panel' => 'chg_panel' ] );
    $defaults = [ 1=>['60','Puissance','W','Charge rapide'], 2=>['5','Coloris','','5 teintes'], 3=>['4','En 1','','4 connectiques'], 4=>['2','Garantie','ans','2 ans constructeur'] ];
    for ( $i = 1; $i <= 4; $i++ ) {
        [ $num, $unit, $suffix, $desc ] = array_values( $defaults[$i] );
        foreach ( ['num'=>$num,'unit'=>$unit,'suffix'=>$suffix,'desc'=>$desc] as $f=>$dv ) {
            $wp_customize->add_setting( "chg_stat_{$i}_{$f}", [ 'default' => $dv, 'sanitize_callback' => 'sanitize_text_field' ] );
            $wp_customize->add_control( "chg_stat_{$i}_{$f}", [ 'label' => "Stat $i — ".ucfirst($f), 'section' => 'chg_stats', 'type' => 'text' ] );
        }
    }
}
add_action( 'customize_register', 'chg_customizer_register' );

add_action( 'wp_head', function() {
    $blue = get_theme_mod( 'chg_color_blue', '#0071e3' );
    echo '<style>:root{--blue:' . esc_attr($blue) . ';--blue-l:' . esc_attr($blue) . ';}</style>';
} );
