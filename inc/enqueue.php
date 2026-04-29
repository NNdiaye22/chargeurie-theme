<?php
/**
 * Chargeurie — Enqueue scripts & styles
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function chg_enqueue_assets() {

    // Main stylesheet
    wp_enqueue_style(
        'chg-main',
        CHG_URI . '/assets/css/main.css',
        [],
        CHG_VERSION
    );

    // WooCommerce override stylesheet
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style(
            'chg-woocommerce',
            CHG_URI . '/assets/css/woocommerce.css',
            [ 'woocommerce-general' ],
            CHG_VERSION
        );
    }

    // GSAP core (CDN)
    wp_enqueue_script(
        'gsap',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
        [],
        '3.12.5',
        true
    );
    // GSAP ScrollTrigger (CDN)
    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
        [ 'gsap' ],
        '3.12.5',
        true
    );

    // Theme JS
    wp_enqueue_script(
        'chg-main',
        CHG_URI . '/assets/js/main.js',
        [ 'gsap', 'gsap-scrolltrigger' ],
        CHG_VERSION,
        true
    );

    // Data for JS
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

// Remove WooCommerce smallscreen styles (we handle responsive ourselves)
add_filter( 'woocommerce_enqueue_styles', function( $styles ) {
    unset( $styles['woocommerce-smallscreen'] );
    return $styles;
} );
