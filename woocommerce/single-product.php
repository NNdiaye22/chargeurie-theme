<?php
/**
 * Chargeurie — woocommerce/single-product.php
 */
get_header();
while ( have_posts() ) : the_post();
    wc_get_template_part( 'content', 'single-product' );
endwhile;
get_footer();
