<?php
/**
 * Chargeurie — Enqueue scripts & styles
 * Cache-busting via filemtime() — chaque modification de fichier invalide le cache navigateur
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function chg_enqueue_assets() {

    // Helper : version = timestamp de dernière modif du fichier (ou CHG_VERSION en fallback)
    $v = function( $rel_path ) {
        $abs = CHG_DIR . '/' . ltrim( $rel_path, '/' );
        return file_exists( $abs ) ? filemtime( $abs ) : CHG_VERSION;
    };

    wp_enqueue_style( 'chg-main', CHG_URI . '/assets/css/main.css', [], $v('assets/css/main.css') );

    wp_enqueue_style( 'chg-animations', CHG_URI . '/assets/css/animations.css', [ 'chg-main' ], $v('assets/css/animations.css') );

    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'chg-woocommerce', CHG_URI . '/assets/css/woocommerce.css', [ 'woocommerce-general' ], $v('assets/css/woocommerce.css') );
    }

    if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) {
        wp_enqueue_style( 'chg-shop', CHG_URI . '/assets/css/shop.css', [ 'chg-main', 'chg-woocommerce' ], $v('assets/css/shop.css') );
    }

    if ( class_exists( 'WooCommerce' ) && is_product() ) {
        wp_enqueue_style( 'chg-product', CHG_URI . '/assets/css/product.css', [ 'chg-main', 'chg-woocommerce', 'chg-shop' ], $v('assets/css/product.css') );
    }

    wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', [], '3.12.5', true );
    wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', [ 'gsap' ], '3.12.5', true );
    wp_enqueue_script( 'chg-main', CHG_URI . '/assets/js/main.js', [ 'gsap', 'gsap-scrolltrigger' ], $v('assets/js/main.js'), true );

    wp_localize_script( 'chg-main', 'chgData', [
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'chg_nonce' ),
        'cartUrl'  => class_exists( 'WooCommerce' ) ? wc_get_cart_url() : '#',
        'shopUrl'  => class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#',
        'themeUri' => CHG_URI,
        'siteUrl'  => get_site_url(),
    ] );
}
add_action( 'wp_enqueue_scripts', 'chg_enqueue_assets' );

add_filter( 'woocommerce_enqueue_styles', function( $styles ) {
    unset( $styles['woocommerce-smallscreen'] );
    return $styles;
} );
