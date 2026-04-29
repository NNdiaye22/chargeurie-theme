<?php
/**
 * Chargeurie — page-checkout.php
 * Template Name: Commande
 */
get_header();
?>
<main class="chg-main chg-container chg-checkout">
  <?php echo class_exists( 'WooCommerce' ) ? do_shortcode( '[woocommerce_checkout]' ) : ''; ?>
</main>
<?php get_footer(); ?>
