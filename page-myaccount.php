<?php
/**
 * Chargeurie — page-myaccount.php
 * Template Name: Mon compte
 */
get_header();
?>
<main class="chg-main chg-container chg-account">
  <header class="page-header">
    <div class="page-header-tag">Mon compte</div>
    <h1 class="page-header-title">
      <?php echo is_user_logged_in() ? 'Bonjour, ' . esc_html( wp_get_current_user()->display_name ) : 'Mon compte'; ?>
    </h1>
  </header>
  <?php echo class_exists( 'WooCommerce' ) ? do_shortcode( '[woocommerce_my_account]' ) : ''; ?>
</main>
<?php get_footer(); ?>
