<?php
/**
 * Chargeurie — functions.php
 *
 * @package Chargeurie
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CHG_VERSION', '1.0.0' );
define( 'CHG_DIR', get_template_directory() );
define( 'CHG_URI', get_template_directory_uri() );

// ── Modules ──────────────────────────────────────
require_once CHG_DIR . '/inc/enqueue.php';
require_once CHG_DIR . '/inc/customizer.php';
require_once CHG_DIR . '/inc/woocommerce.php';

// ── Prix «A partir de» produits variables ────────────
add_filter( 'woocommerce_variable_price_html', function( $price, $product ) {
    $min = wc_price( $product->get_variation_price( 'min', true ) );
    return sprintf( 'A partir de %s', $min );
}, 10, 2 );

// ── Masquer le stock restant côté client ─────────────
// Supprime le message «X en stock» de WooCommerce partout sauf Admin
add_filter( 'woocommerce_get_availability', function( $availability, $product ) {
    if ( ! is_admin() ) {
        // On conserve uniquement le statut En stock / Épuisé, sans le chiffre
        if ( isset( $availability['availability'] ) ) {
            $txt = $availability['availability'];
            // Si le message contient un chiffre, on le remplace par un label générique
            if ( preg_match( '/\d/', $txt ) ) {
                $availability['availability'] = __( 'Stock limité', 'chargeurie' );
            }
        }
    }
    return $availability;
}, 20, 2 );

// ── Badge produit ──────────────────────────────
function chg_card_badge( $product ) {
    if ( ! $product ) return null;

    if ( $product->is_type( 'variable' ) ) {
        $any_sale = $any_low = $any_backorder = false;
        $all_out  = true;
        foreach ( $product->get_available_variations() as $v ) {
            $vobj    = wc_get_product( $v['variation_id'] );
            $v_stock = $vobj ? $vobj->get_stock_quantity() : null;
            $v_back  = $vobj ? $vobj->backorders_allowed() : false;
            $v_low   = ( $v['is_in_stock'] && $v_stock !== null && $v_stock > 0 && $v_stock <= 5 );
            $on_sale = $v['display_regular_price'] > $v['display_price'];
            if ( $v['is_in_stock'] || $v_back ) $all_out = false;
            if ( $on_sale ) $any_sale      = true;
            if ( $v_low )   $any_low       = true;
            if ( $v_back && ! $v['is_in_stock'] ) $any_backorder = true;
        }
        if ( $all_out )       return [ 'id' => 'out',       'label' => '&Eacute;puis&eacute;' ];
        if ( $any_backorder ) return [ 'id' => 'backorder', 'label' => 'Bient&ocirc;t dispo' ];
        if ( $any_low )       return [ 'id' => 'low',       'label' => 'Stock limit&eacute;' ];
        if ( $any_sale )      return [ 'id' => 'promo',     'label' => 'Promo' ];
        return null;
    }

    $in_stock   = $product->is_in_stock();
    $backorders = $product->backorders_allowed();
    $stock_qty  = $product->get_stock_quantity();
    $low_stock  = ( $in_stock && $stock_qty !== null && $stock_qty > 0 && $stock_qty <= 5 );
    $sale_price = $product->get_sale_price();

    if ( ! $in_stock && $backorders ) return [ 'id' => 'backorder', 'label' => 'Bient&ocirc;t dispo' ];
    if ( ! $in_stock )                return [ 'id' => 'out',       'label' => '&Eacute;puis&eacute;' ];
    if ( $low_stock )                 return [ 'id' => 'low',       'label' => 'Stock limit&eacute;' ]; /* sans chiffre */
    if ( $sale_price )                return [ 'id' => 'promo',     'label' => 'Promo' ];
    return null;
}

// ── Theme setup ───────────────────────────────
function chg_theme_setup() {
    load_theme_textdomain( 'chargeurie', CHG_DIR . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', [
        'height'               => 60,
        'width'                => 200,
        'flex-height'          => true,
        'flex-width'           => true,
        'unlink-homepage-logo' => true,
    ] );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width' => 600,
        'single_image_width'    => 900,
        'product_grid'          => [
            'default_columns' => 3,
            'default_rows'    => 4,
            'min_columns'     => 1,
            'max_columns'     => 4,
        ],
    ] );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    register_nav_menus( [
        'primary'  => __( 'Menu Principal', 'chargeurie' ),
        'footer_1' => __( 'Footer — Produits', 'chargeurie' ),
        'footer_2' => __( 'Footer — Informations', 'chargeurie' ),
        'footer_3' => __( 'Footer — Service Client', 'chargeurie' ),
    ] );
    add_theme_support( 'automatic-feed-links' );

    add_image_size( 'chg-logo',             200,  60,   false );
    add_image_size( 'chg-product-card',     600,  800,  true  );
    add_image_size( 'chg-product-featured', 900,  1200, true  );
    add_image_size( 'chg-hero',             1920, 1080, true  );
}
add_action( 'after_setup_theme', 'chg_theme_setup' );

// ── Widgets ──────────────────────────────────
function chg_widgets_init() {
    $cols = [
        'footer-1' => 'Footer — Présentation',
        'footer-2' => 'Footer — Produits',
        'footer-3' => 'Footer — Informations',
        'footer-4' => 'Footer — Service Client',
    ];
    foreach ( $cols as $id => $name ) {
        register_sidebar( [
            'name'          => __( $name, 'chargeurie' ),
            'id'            => $id,
            'before_widget' => '<div class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ] );
    }
}
add_action( 'widgets_init', 'chg_widgets_init' );

// ── Helpers ─────────────────────────────────
function chg_the_logo( $return = false ) {
    $logo_id = get_theme_mod( 'custom_logo' );
    if ( $logo_id ) {
        $logo_url = wp_get_attachment_image_url( $logo_id, 'chg-logo' );
        if ( ! $logo_url ) {
            $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
        }
        $output = '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="site-logo-img" width="200" height="60" loading="eager">';
    } else {
        $output = '<span class="site-logo-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
    }
    if ( $return ) return $output;
    echo $output;
}

function chg_option( $key, $default = '' ) {
    return get_theme_mod( $key, $default );
}

// ── CSS pages WooCommerce ─────────────────────
add_action( 'wp_enqueue_scripts', function() {
    if ( is_wc_endpoint_url() || is_account_page() || is_checkout() ) {
        wp_enqueue_style( 'chg-wc-pages', CHG_URI . '/assets/css/wc-pages.css', [], CHG_VERSION );
    }
    if ( is_page_template( 'page-privacy-policy.php' ) ) {
        wp_enqueue_style( 'chg-legal', CHG_URI . '/assets/css/legal.css', [], CHG_VERSION );
    }
} );

// ── Menu Mon Compte simplifié ──────────────────
add_filter( 'woocommerce_account_menu_items', function( $items ) {
    return [
        'dashboard'       => 'Tableau de bord',
        'orders'          => 'Mes commandes',
        'edit-address'    => 'Mes adresses',
        'edit-account'    => 'Mes informations',
        'customer-logout' => 'Déconnexion',
    ];
} );

// ── Excerpt ───────────────────────────────────
add_filter( 'excerpt_length', fn() => 20 );
