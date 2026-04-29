<?php
/**
 * Chargeurie — page-cart.php
 * Template Name: Panier
 */
get_header();
?>
<main class="chg-main chg-container chg-cart">
  <header class="page-header">
    <div class="page-header-tag">Panier</div>
    <h1 class="page-header-title">Votre panier</h1>
  </header>
  <?php echo class_exists( 'WooCommerce' ) ? do_shortcode( '[woocommerce_cart]' ) : ''; ?>
</main>
<?php get_footer(); ?>
