<?php
/**
 * Chargeurie — Enqueue scripts & styles
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function chg_enqueue_assets() {

    wp_enqueue_style( 'chg-main', CHG_URI . '/assets/css/main.css', [], CHG_VERSION );

    wp_enqueue_style( 'chg-animations', CHG_URI . '/assets/css/animations.css', [ 'chg-main' ], CHG_VERSION );

    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'chg-woocommerce', CHG_URI . '/assets/css/woocommerce.css', [ 'woocommerce-general' ], CHG_VERSION );
    }

    if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) {
        wp_enqueue_style( 'chg-shop', CHG_URI . '/assets/css/shop.css', [ 'chg-main', 'chg-woocommerce' ], CHG_VERSION );
    }

    // CSS dédié fiche produit — chargé uniquement sur is_product()
    if ( class_exists( 'WooCommerce' ) && is_product() ) {
        wp_enqueue_style( 'chg-product', CHG_URI . '/assets/css/product.css', [ 'chg-main', 'chg-woocommerce', 'chg-shop' ], CHG_VERSION );
    }

    wp_enqueue_script( 'gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', [], '3.12.5', true );
    wp_enqueue_script( 'gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', [ 'gsap' ], '3.12.5', true );
    wp_enqueue_script( 'chg-main', CHG_URI . '/assets/js/main.js', [ 'gsap', 'gsap-scrolltrigger' ], CHG_VERSION, true );

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
