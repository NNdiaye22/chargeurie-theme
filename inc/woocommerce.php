<?php
/**
 * Chargeurie — WooCommerce hooks & overrides
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Remove default breadcrumbs
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Remove default sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Replace default page wrappers with our own
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', function() {
    echo '<main class="chg-wc-main"><div class="chg-container">';
} );
add_action( 'woocommerce_after_main_content', function() {
    echo '</div></main>';
} );

// AJAX Add to Cart
add_action( 'wp_ajax_chg_add_to_cart',        'chg_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_chg_add_to_cart', 'chg_ajax_add_to_cart' );
function chg_ajax_add_to_cart() {
    check_ajax_referer( 'chg_nonce', 'nonce' );
    $product_id = intval( $_POST['product_id'] ?? 0 );
    $quantity   = intval( $_POST['quantity']   ?? 1 );
    if ( ! $product_id ) wp_send_json_error( [ 'message' => 'Produit introuvable.' ] );

    $added = WC()->cart->add_to_cart( $product_id, $quantity );
    if ( $added ) {
        wp_send_json_success( [
            'message'    => 'Produit ajouté.',
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_url'   => wc_get_cart_url(),
        ] );
    } else {
        wp_send_json_error( [ 'message' => 'Impossible d\'ajouter ce produit.' ] );
    }
}

// 3 colonnes sur la boutique
add_filter( 'loop_shop_columns', fn() => 3 );

// Retirer le badge promo natif WC (on utilise chg_card_badge)
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
