<?php
/**
 * Chargeurie — woocommerce/content-product.php
 */
global $product;
if ( empty( $product ) || ! $product->is_visible() ) return;
$badge = function_exists('chg_card_badge') ? chg_card_badge( $product ) : null;
?>
<div class="product-card" <?php wc_product_class(); ?> data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
  <a href="<?php echo esc_url( get_permalink() ); ?>" class="card-img-wrap">
    <?php if ( $badge ) : ?>
      <div class="card-badge badge-<?php echo esc_attr( $badge['id'] ); ?>"><?php echo $badge['label']; ?></div>
    <?php endif; ?>
    <?php if ( has_post_thumbnail() ) : ?>
      <?php the_post_thumbnail( 'chg-product-card', ['class' => 'slide-product-img', 'loading' => 'lazy'] ); ?>
    <?php else : ?>
      <div class="card-placeholder"><span class="card-placeholder-icon">&#9889;</span></div>
    <?php endif; ?>
  </a>
  <div class="card-info">
    <div class="card-name"><?php the_title(); ?></div>
    <div class="card-bottom">
      <div class="card-price"><?php echo $product->get_price_html(); ?></div>
      <a href="<?php echo esc_url( get_permalink() ); ?>" class="card-add">Voir</a>
    </div>
  </div>
</div>
