<?php
/**
 * Chargeurie — woocommerce/archive-product.php
 */
get_header();
?>
<main class="chg-wc-main chg-container">
  <header class="page-header">
    <div class="page-header-tag">Boutique</div>
    <h1 class="page-header-title"><?php woocommerce_page_title(); ?></h1>
  </header>
  <?php if ( woocommerce_product_loop() ) : ?>
    <?php woocommerce_product_loop_start(); ?>
      <div class="products-grid shop-products-grid">
        <?php while ( have_posts() ) : the_post(); ?>
          <?php wc_get_template_part( 'content', 'product' ); ?>
        <?php endwhile; ?>
      </div>
    <?php woocommerce_product_loop_end(); ?>
    <?php woocommerce_pagination(); ?>
  <?php else : ?>
    <?php do_action( 'woocommerce_no_products_found' ); ?>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
