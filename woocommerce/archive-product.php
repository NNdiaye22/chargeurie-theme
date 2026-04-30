<?php
/**
 * Chargeurie — woocommerce/archive-product.php
 * Page boutique / collections — charte premium Chargeurie
 */
get_header();
?>

<main class="chg-shop">

  <section class="shop-hero">
    <div class="shop-hero-inner">
      <div class="shop-hero-tag">Gamme complète</div>
      <h1 class="shop-hero-title">
        <span class="solid">Nos</span>
        <span class="outline">câbles</span>
      </h1>
      <p class="shop-hero-sub">Des accessoires de charge pensés pour être portés, pas rangés dans un tiroir.</p>
    </div>
  </section>

  <div class="shop-bar chg-container">
    <div class="shop-bar-left"><?php woocommerce_result_count(); ?></div>
    <div class="shop-bar-right"><?php woocommerce_catalog_ordering(); ?></div>
  </div>

  <div class="chg-container shop-content">
    <?php if ( woocommerce_product_loop() ) : ?>
      <div class="products-grid shop-grid" id="shopGrid">
        <?php while ( have_posts() ) : the_post(); ?>
          <?php wc_get_template_part( 'content', 'product' ); ?>
        <?php endwhile; ?>
      </div>
      <div class="shop-pagination"><?php woocommerce_pagination(); ?></div>
    <?php else : ?>
      <div class="shop-empty">
        <p class="shop-empty-text">Aucun produit disponible pour le moment.</p>
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn-dark">Retour à l'accueil</a>
      </div>
    <?php endif; ?>
  </div>

</main>

<?php get_footer(); ?>
