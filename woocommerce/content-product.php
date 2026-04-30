<?php
defined( 'ABSPATH' ) || exit;
global $product;
if ( empty( $product ) || ! $product->is_visible() ) return;
$badge   = function_exists( 'chg_card_badge' ) ? chg_card_badge( $product ) : null;
$img_url = get_the_post_thumbnail_url( $product->get_id(), 'chg-product-card' );
?>
<li <?php wc_product_class( 'chg-product-card', $product ); ?> data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
  <?php if ( $badge ) : ?>
    <div class="card-badge badge-<?php echo esc_attr( $badge['id'] ); ?>"><?php echo $badge['label']; ?></div>
  <?php endif; ?>
  <a href="<?php the_permalink(); ?>" class="card-img-wrap">
    <?php if ( $img_url ) : ?>
      <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" class="card-img">
    <?php else : ?>
      <div class="card-img-placeholder">&#128225;</div>
    <?php endif; ?>
  </a>
  <div class="card-body">
    <div class="card-cat"><?php echo wc_get_product_category_list( $product->get_id() ); ?></div>
    <h3 class="card-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <div class="card-price"><?php echo $product->get_price_html(); ?></div>
    <a href="<?php the_permalink(); ?>" class="card-add">Voir le produit</a>
  </div>
</li>
