<?php
/**
 * Chargeurie — woocommerce/single-product.php
 * Fiche produit premium — charte Chargeurie
 */
get_header();
while ( have_posts() ) :
  the_post();
  global $product;
?>

<main class="chg-single-product">
  <div class="sp-container chg-container">

    <!-- Galerie -->
    <div class="sp-gallery">
      <?php $badge = function_exists('chg_card_badge') ? chg_card_badge($product) : null; ?>
      <?php if ($badge) : ?>
        <div class="sp-badge badge-<?php echo esc_attr($badge['id']); ?>"><?php echo $badge['label']; ?></div>
      <?php endif; ?>
      <div class="sp-img-main">
        <?php $image_id = $product->get_image_id(); ?>
        <?php if ($image_id) : ?>
          <?php echo wp_get_attachment_image($image_id, 'chg-product-featured', false, ['class' => 'sp-main-img', 'id' => 'spMainImg']); ?>
        <?php else : ?>
          <div class="sp-img-placeholder"><span>&#9889;</span></div>
        <?php endif; ?>
      </div>
      <?php $gallery_ids = $product->get_gallery_image_ids(); ?>
      <?php if (!empty($gallery_ids)) : ?>
        <div class="sp-thumbs">
          <?php if ($image_id) : ?>
            <button class="sp-thumb active" data-full="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'chg-product-featured')); ?>">
              <?php echo wp_get_attachment_image($image_id, [80,80]); ?>
            </button>
          <?php endif; ?>
          <?php foreach ($gallery_ids as $gid) : ?>
            <button class="sp-thumb" data-full="<?php echo esc_url(wp_get_attachment_image_url($gid, 'chg-product-featured')); ?>">
              <?php echo wp_get_attachment_image($gid, [80,80]); ?>
            </button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Infos produit -->
    <div class="sp-summary">
      <nav class="sp-breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a>
        <span>&rsaquo;</span>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Boutique</a>
        <span>&rsaquo;</span>
        <span><?php the_title(); ?></span>
      </nav>

      <h1 class="sp-title"><?php the_title(); ?></h1>
      <div class="sp-price"><?php echo $product->get_price_html(); ?></div>

      <?php $short = $product->get_short_description(); ?>
      <?php if ($short) : ?>
        <div class="sp-short-desc"><?php echo wp_kses_post($short); ?></div>
      <?php endif; ?>

      <div class="sp-form"><?php woocommerce_template_single_add_to_cart(); ?></div>

      <ul class="sp-trust">
        <li><span class="trust-icon">&#9889;</span><span>Charge rapide <strong>60W</strong></span></li>
        <li><span class="trust-icon">&#128666;</span><span>Livraison offerte dès <strong>35 €</strong></span></li>
        <li><span class="trust-icon">&#128260;</span><span>Retours sous <strong>30 jours</strong></span></li>
        <li><span class="trust-icon">&#9989;</span><span>Garantie <strong>2 ans</strong></span></li>
      </ul>

      <?php $desc = $product->get_description(); ?>
      <?php if ($desc) : ?>
        <details class="sp-accordion" open>
          <summary class="sp-accordion-title">Description</summary>
          <div class="sp-accordion-body"><?php echo wp_kses_post($desc); ?></div>
        </details>
      <?php endif; ?>

      <details class="sp-accordion">
        <summary class="sp-accordion-title">Compatibilité</summary>
        <div class="sp-accordion-body">
          <p>Compatible avec tous les appareils USB-C : iPhone 15+, Samsung, Google Pixel, MacBook, iPad et tout appareil à port USB-C.</p>
        </div>
      </details>
    </div>
  </div>

  <?php
  $related = wc_get_related_products($product->get_id(), 3);
  if (!empty($related)) :
  ?>
    <section class="sp-related chg-container">
      <div class="sp-related-header">
        <div class="products-tag">Vous aimerez aussi</div>
        <h2 class="sp-related-title">Produits associés</h2>
      </div>
      <div class="products-grid">
        <?php foreach ($related as $related_id) :
          $p = wc_get_product($related_id);
          if (!$p || !$p->is_visible()) continue;
          $b = function_exists('chg_card_badge') ? chg_card_badge($p) : null;
        ?>
          <div class="product-card" data-product-id="<?php echo esc_attr($related_id); ?>">
            <a href="<?php echo esc_url(get_permalink($related_id)); ?>" class="card-img-wrap">
              <?php if ($b) : ?><div class="card-badge badge-<?php echo esc_attr($b['id']); ?>"><?php echo $b['label']; ?></div><?php endif; ?>
              <?php $tid = $p->get_image_id(); ?>
              <?php if ($tid) : echo wp_get_attachment_image($tid, 'chg-product-card', false, ['class' => 'slide-product-img', 'loading' => 'lazy']);
              else : ?><div class="card-placeholder"><span class="card-placeholder-icon">&#9889;</span></div><?php endif; ?>
            </a>
            <div class="card-info">
              <div class="card-name"><?php echo esc_html($p->get_name()); ?></div>
              <div class="card-bottom">
                <div class="card-price"><?php echo $p->get_price_html(); ?></div>
                <a href="<?php echo esc_url(get_permalink($related_id)); ?>" class="card-add">Voir</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

</main>

<script>
document.querySelectorAll('.sp-thumb').forEach(function(btn){
  btn.addEventListener('click', function(){
    document.querySelectorAll('.sp-thumb').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    var img = document.getElementById('spMainImg');
    if (img) img.src = btn.dataset.full;
  });
});
</script>

<?php endwhile; get_footer(); ?>
