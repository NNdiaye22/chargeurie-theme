<?php
/**
 * Chargeurie — Customizer options
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function chg_customizer_register( $wp_customize ) {

    // Panel
    $wp_customize->add_panel( 'chg_panel', array(
        'title'    => 'Chargeurie — Thème',
        'priority' => 30,
    ) );

    // ---- Section : Identité ----
    $wp_customize->add_section( 'chg_identity', array(
        'title' => 'Identité',
        'panel' => 'chg_panel',
    ) );
    $fields_identity = array(
        'chg_tagline'       => array( 'default' => 'Câbles & Accessoires de Charge — France', 'label' => 'Sous-titre nav' ),
        'chg_ticker_text'   => array( 'default' => 'Charge Rapide 60 W · USB-C 3A · 5 Coloris · Lanière Téléphone · Câble Bracelet · 4-en-1 · Livraison offerte dès 35 € · Garantie 2 ans ·', 'label' => 'Ticker texte' ),
        'chg_instagram_url' => array( 'default' => '#', 'label' => 'Instagram URL' ),
        'chg_tiktok_url'    => array( 'default' => '#', 'label' => 'TikTok URL' ),
        'chg_linkedin_url'  => array( 'default' => '#', 'label' => 'LinkedIn URL' ),
        'chg_contact_email' => array( 'default' => 'contact@chargeurie.fr', 'label' => 'Email de contact' ),
    );
    foreach ( $fields_identity as $key => $args ) {
        $wp_customize->add_setting( $key, array( 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( $key, array( 'label' => $args['label'], 'section' => 'chg_identity', 'type' => 'text' ) );
    }

    // ---- Section : Hero ----
    $wp_customize->add_section( 'chg_hero', array(
        'title' => "Page d'accueil — Hero",
        'panel' => 'chg_panel',
    ) );
    $fields_hero = array(
        'chg_hero_line1'    => array( 'default' => 'PORTEZ',   'label' => 'Ligne 1 (plein)' ),
        'chg_hero_line2'    => array( 'default' => 'VOTRE',    'label' => 'Ligne 2 (outline)' ),
        'chg_hero_line3'    => array( 'default' => 'CHARGE',   'label' => 'Ligne 3 (plein)' ),
        'chg_hero_subtitle' => array( 'default' => 'Des câbles pensés pour être portés au quotidien — autour du cou, au poignet, en porte-clé.', 'label' => 'Sous-titre hero' ),
        'chg_hero_cta'      => array( 'default' => 'Découvrir la gamme', 'label' => 'CTA hero' ),
    );
    foreach ( $fields_hero as $key => $args ) {
        $wp_customize->add_setting( $key, array( 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( $key, array( 'label' => $args['label'], 'section' => 'chg_hero', 'type' => 'text' ) );
    }

    $wp_customize->add_setting( 'chg_hero_img_desktop', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'chg_hero_img_desktop', array(
        'label'       => '🖥 Image Hero — Desktop (recommandé : 1920×1080)',
        'description' => 'Affichée sur écrans > 768px.',
        'section'     => 'chg_hero',
        'mime_type'   => 'image',
    ) ) );

    $wp_customize->add_setting( 'chg_hero_img_mobile', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'chg_hero_img_mobile', array(
        'label'       => '📱 Image Hero — Mobile (recommandé : 750×1334)',
        'description' => "Affichée sur écrans ≤ 768px. Si vide, l'image desktop sera utilisée.",
        'section'     => 'chg_hero',
        'mime_type'   => 'image',
    ) ) );

    $wp_customize->add_setting( 'chg_hero_overlay', array(
        'default'           => '0.55',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'chg_hero_overlay', array(
        'label'       => 'Opacité overlay sombre (0 = transparent, 1 = noir)',
        'description' => 'Assombrit la bannière pour garder le texte lisible.',
        'section'     => 'chg_hero',
        'type'        => 'range',
        'input_attrs' => array( 'min' => '0', 'max' => '1', 'step' => '0.05' ),
    ) );

    // ---- Section : Manifeste ----
    $wp_customize->add_section( 'chg_manifesto', array(
        'title' => "Page d'accueil — Manifeste",
        'panel' => 'chg_panel',
    ) );
    $wp_customize->add_setting( 'chg_manifesto_text', array( 'default' => 'Conçu pour être porté, pas rangé.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'chg_manifesto_text', array( 'label' => 'Phrase manifeste', 'section' => 'chg_manifesto', 'type' => 'text' ) );
    $wp_customize->add_setting( 'chg_manifesto_sub', array( 'default' => "La technologie la plus utile est celle que vous avez toujours sur vous. Chargeurie conçoit des accessoires qui disparaissent dans votre quotidien — jusqu'au moment où vous en avez besoin.", 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'chg_manifesto_sub', array( 'label' => 'Sous-texte manifeste', 'section' => 'chg_manifesto', 'type' => 'textarea' ) );

    // ---- Section : Stats ----
    $wp_customize->add_section( 'chg_stats', array( 'title' => "Page d'accueil — Stats", 'panel' => 'chg_panel' ) );
    $stat_defaults = array(
        1 => array( 'num' => '60', 'unit' => 'Puissance',  'suffix' => 'W',   'desc' => "Charge rapide sur l'ensemble de la gamme" ),
        2 => array( 'num' => '5',  'unit' => 'Coloris',    'suffix' => '',    'desc' => 'Chaque produit décliné en plusieurs teintes' ),
        3 => array( 'num' => '4',  'unit' => 'En 1',       'suffix' => '',    'desc' => 'Compatibilité universelle avec un seul câble' ),
        4 => array( 'num' => '2',  'unit' => 'Garantie',   'suffix' => 'ans', 'desc' => 'Garantie constructeur sur tous les produits' ),
    );
    for ( $i = 1; $i <= 4; $i++ ) {
        foreach ( array( 'num', 'unit', 'suffix', 'desc' ) as $f ) {
            $wp_customize->add_setting( "chg_stat_{$i}_{$f}", array( 'default' => $stat_defaults[$i][$f], 'sanitize_callback' => 'sanitize_text_field' ) );
            $wp_customize->add_control( "chg_stat_{$i}_{$f}", array( 'label' => "Stat $i — " . ucfirst($f), 'section' => 'chg_stats', 'type' => 'text' ) );
        }
    }

    // ================================================================
    // ---- Section : Gamme (produits accueil) ----
    // ================================================================
    $wp_customize->add_section( 'chg_gamme', array(
        'title'       => "Page d'accueil — Section Gamme",
        'description' => 'Choisissez la catégorie à afficher, le nombre de produits et personnalisez les textes.',
        'panel'       => 'chg_panel',
    ) );

    // Tag
    $wp_customize->add_setting( 'chg_gamme_tag', array(
        'default'           => 'Gamme',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'chg_gamme_tag', array(
        'label'   => 'Tag (ex : Gamme, Collection…)',
        'section' => 'chg_gamme',
        'type'    => 'text',
    ) );

    // Titre
    $wp_customize->add_setting( 'chg_gamme_title', array(
        'default'           => 'Nos Lanieres',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'chg_gamme_title', array(
        'label'   => 'Titre de la section',
        'section' => 'chg_gamme',
        'type'    => 'text',
    ) );

    // Nombre de produits
    $wp_customize->add_setting( 'chg_gamme_count', array(
        'default'           => '3',
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'chg_gamme_count', array(
        'label'       => 'Nombre de produits affichés (1 à 6)',
        'section'     => 'chg_gamme',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 1, 'max' => 6 ),
    ) );

    // ---- Catégorie : menu déroulant dynamique ----
    // On construit la liste depuis les termes WooCommerce existants.
    $cat_choices = array( '' => '— Tous les produits (aucun filtre) —' );
    if ( class_exists( 'WooCommerce' ) ) {
        $wc_cats = get_terms( array(
            'taxonomy'   => 'product_cat',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,   // affiche aussi les catégories vides
        ) );
        if ( ! is_wp_error( $wc_cats ) && ! empty( $wc_cats ) ) {
            foreach ( $wc_cats as $cat ) {
                // La clé = slug (utilisé dans la requête produits)
                // La valeur = nom affiché dans le Customizer
                $cat_choices[ $cat->slug ] = $cat->name . ' (' . $cat->count . ' produit' . ( $cat->count > 1 ? 's' : '' ) . ')';
            }
        }
    }

    $wp_customize->add_setting( 'chg_gamme_category', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'chg_gamme_category', array(
        'label'       => 'Catégorie de produits',
        'description' => "Sélectionnez une catégorie pour filtrer les produits affichés. Choisissez \u00ab Tous les produits \u00bb pour afficher les derniers publiés.",
        'section'     => 'chg_gamme',
        'type'        => 'select',
        'choices'     => $cat_choices,
    ) );

    // Texte "Voir tout"
    $wp_customize->add_setting( 'chg_gamme_viewall', array(
        'default'           => 'Voir tout',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'chg_gamme_viewall', array(
        'label'   => 'Texte lien "Voir tout"',
        'section' => 'chg_gamme',
        'type'    => 'text',
    ) );

    // ================================================================
    // ---- Section : Reveal (Produit Vedette) ----
    // ================================================================
    $wp_customize->add_section( 'chg_reveal', array(
        'title'       => "Page d'accueil — Section Produit Vedette",
        'description' => 'Modifiez la section mise en avant produit (La Lanière USB-C 3A par défaut).',
        'panel'       => 'chg_panel',
    ) );

    $wp_customize->add_setting( 'chg_reveal_title1', array( 'default' => 'La Lanière', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'chg_reveal_title1', array( 'label' => 'Titre — Ligne 1', 'section' => 'chg_reveal', 'type' => 'text' ) );

    $wp_customize->add_setting( 'chg_reveal_title2', array( 'default' => 'USB-C 3A', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'chg_reveal_title2', array( 'label' => 'Titre — Ligne 2 (italique)', 'section' => 'chg_reveal', 'type' => 'text' ) );

    $wp_customize->add_setting( 'chg_reveal_desc', array(
        'default'           => "Notre accessoire le plus emblématique. Câble USB-C 3A intégré dans une lanière portée au cou. La charge toujours à portée de main, le téléphone jamais perdu.",
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'chg_reveal_desc', array( 'label' => 'Paragraphe description', 'section' => 'chg_reveal', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'chg_reveal_cta_text', array( 'default' => 'Découvrir', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'chg_reveal_cta_text', array( 'label' => 'Texte du bouton', 'section' => 'chg_reveal', 'type' => 'text' ) );

    $wp_customize->add_setting( 'chg_reveal_cta_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'chg_reveal_cta_url', array(
        'label'       => 'URL du bouton',
        'description' => 'Laissez vide pour pointer vers la boutique automatiquement.',
        'section'     => 'chg_reveal',
        'type'        => 'url',
    ) );

    $spec_defaults = array(
        1 => array( 'label' => 'Puissance',      'value' => '3A — Charge rapide 18 W' ),
        2 => array( 'label' => 'Longueur câble', 'value' => '13,5 cm' ),
        3 => array( 'label' => 'Coloris',        'value' => '5 teintes disponibles' ),
        4 => array( 'label' => 'Compatibilité',  'value' => 'iPhone & Android USB-C' ),
        5 => array( 'label' => 'Livraison',      'value' => 'Offerte dès 35 €' ),
    );
    for ( $i = 1; $i <= 5; $i++ ) {
        $wp_customize->add_setting( "chg_reveal_spec_{$i}_label", array( 'default' => $spec_defaults[$i]['label'], 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "chg_reveal_spec_{$i}_label", array( 'label' => "Spec $i — Intitulé", 'section' => 'chg_reveal', 'type' => 'text' ) );
        $wp_customize->add_setting( "chg_reveal_spec_{$i}_value", array( 'default' => $spec_defaults[$i]['value'], 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "chg_reveal_spec_{$i}_value", array( 'label' => "Spec $i — Valeur", 'section' => 'chg_reveal', 'type' => 'text' ) );
    }

    // ---- Section : Couleurs ----
    $wp_customize->add_section( 'chg_colors', array( 'title' => 'Couleurs', 'panel' => 'chg_panel' ) );
    $wp_customize->add_setting( 'chg_color_blue', array( 'default' => '#0071e3', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'chg_color_blue', array( 'label' => 'Couleur accent (bleu)', 'section' => 'chg_colors' ) ) );

    // ---- Section : Formulaires ----
    $wp_customize->add_section( 'chg_forms', array( 'title' => 'Formulaires', 'panel' => 'chg_panel' ) );
    $wp_customize->add_setting( 'chg_cf7_contact_id',     array( 'default' => '', 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'chg_cf7_contact_id',     array( 'label' => 'CF7 — ID formulaire contact', 'section' => 'chg_forms', 'type' => 'number' ) );
    $wp_customize->add_setting( 'chg_wpforms_contact_id', array( 'default' => '', 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'chg_wpforms_contact_id', array( 'label' => 'WPForms — ID formulaire contact', 'section' => 'chg_forms', 'type' => 'number' ) );
    $wp_customize->add_setting( 'chg_mc4wp_form_id',      array( 'default' => '', 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'chg_mc4wp_form_id',      array( 'label' => 'Mailchimp MC4WP — ID formulaire newsletter', 'section' => 'chg_forms', 'type' => 'number' ) );
}
add_action( 'customize_register', 'chg_customizer_register' );

// Live preview : custom property CSS
add_action( 'wp_head', function() {
    $blue = get_theme_mod( 'chg_color_blue', '#0071e3' );
    echo '<style id="chg-live-color">:root{--blue:' . esc_attr( $blue ) . ';}</style>';
} );
