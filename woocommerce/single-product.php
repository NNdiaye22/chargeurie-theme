<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main class="chg-wc-main chg-single-product">
  <?php while ( have_posts() ) : the_post(); ?>
    <?php wc_get_template_part( 'content', 'single-product' ); ?>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
