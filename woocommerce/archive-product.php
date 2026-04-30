<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main class="chg-wc-main">
  <div class="chg-container">
    <header class="page-header">
      <div class="page-header-tag">Boutique</div>
      <h1 class="page-header-title"><?php woocommerce_page_title(); ?></h1>
    </header>
    <?php if ( woocommerce_product_loop() ) : ?>
      <?php do_action( 'woocommerce_before_shop_loop' ); ?>
      <div class="products-grid">
        <?php woocommerce_product_loop_start(); ?>
        <?php while ( have_posts() ) : the_post(); ?>
          <?php wc_get_template_part( 'content', 'product' ); ?>
        <?php endwhile; ?>
        <?php woocommerce_product_loop_end(); ?>
      </div>
      <?php do_action( 'woocommerce_after_shop_loop' ); ?>
    <?php else : ?>
      <?php do_action( 'woocommerce_no_products_found' ); ?>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
