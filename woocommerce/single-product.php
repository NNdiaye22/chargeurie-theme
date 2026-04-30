<?php
/**
 * Chargeurie — woocommerce/single-product.php
 * Fiche produit premium v2 — sans emoji
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
      <div class="sp-img-main">
        <?php if ($badge) : ?>
          <div class="sp-badge badge-<?php echo esc_attr($badge['id']); ?>"><?php echo $badge['label']; ?></div>
        <?php endif; ?>
        <?php $image_id = $product->get_image_id(); ?>
        <?php if ($image_id) : ?>
          <?php echo wp_get_attachment_image($image_id, 'chg-product-featured', false, ['class' => 'sp-main-img', 'id' => 'spMainImg']); ?>
        <?php else : ?>
          <div class="sp-img-placeholder">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M8 1.5L2.5 8H7L5.5 12.5L12 6H7.5L8 1.5Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round" opacity=".2"/></svg>
          </div>
        <?php endif; ?>
      </div>

      <?php $gallery_ids = $product->get_gallery_image_ids(); ?>
      <?php if (!empty($gallery_ids)) : ?>
        <div class="sp-thumbs">
          <?php if ($image_id) : ?>
            <button class="sp-thumb active" data-full="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'chg-product-featured')); ?>" aria-label="Image principale">
              <?php echo wp_get_attachment_image($image_id, [80,80]); ?>
            </button>
          <?php endif; ?>
          <?php foreach ($gallery_ids as $gid) : ?>
            <button class="sp-thumb" data-full="<?php echo esc_url(wp_get_attachment_image_url($gid, 'chg-product-featured')); ?>" aria-label="Vue alternative">
              <?php echo wp_get_attachment_image($gid, [80,80]); ?>
            </button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Infos produit -->
    <div class="sp-summary">

      <nav class="sp-breadcrumb" aria-label="Fil d'Ariane">
        <a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a>
        <span aria-hidden="true">&rsaquo;</span>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Boutique</a>
        <span aria-hidden="true">&rsaquo;</span>
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
        <li>
          <span class="trust-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 14 14" fill="none"><path d="M8 1.5L2.5 8H7L5.5 12.5L12 6H7.5L8 1.5Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
          </span>
          <span>Charge rapide <strong>60 W</strong></span>
        </li>
        <li>
          <span class="trust-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 14 14" fill="none"><path d="M1 9V5a1 1 0 011-1h6v5M1 9h8M1 9a1.5 1.5 0 003 0m5 0a1.5 1.5 0 003 0M12 9V7.5L10 5H8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
          <span>Livraison offerte dès <strong>35 €</strong></span>
        </li>
        <li>
          <span class="trust-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 14 14" fill="none"><path d="M2 7A5 5 0 107 2H4m0 0L2 4m2-2L6 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
          <span>Retours sous <strong>30 jours</strong></span>
        </li>
        <li>
          <span class="trust-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 14 14" fill="none"><path d="M7 1L2 3V7c0 2.8 2.2 4.7 5 5.5C9.8 11.7 12 9.8 12 7V3L7 1Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
          </span>
          <span>Garantie <strong>2 ans</strong></span>
        </li>
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

      <details class="sp-accordion">
        <summary class="sp-accordion-title">Livraison & Retours</summary>
        <div class="sp-accordion-body">
          <p>Expédition sous 24 h (jours ouvrables). Livraison offerte dès 35 € d’achat. Retours acceptables sous 30 jours — produit non utilisé dans son emballage d’origine.</p>
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
        <h2 class="sp-related-title">Dans la même gamme</h2>
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
              else : ?>
                <div class="card-placeholder">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M8 1.5L2.5 8H7L5.5 12.5L12 6H7.5L8 1.5Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round" opacity=".15"/></svg>
                </div>
              <?php endif; ?>
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
(function(){
  var thumbs = document.querySelectorAll('.sp-thumb');
  var mainImg = document.getElementById('spMainImg');
  thumbs.forEach(function(btn){
    btn.addEventListener('click', function(){
      thumbs.forEach(function(b){ b.classList.remove('active'); });
      btn.classList.add('active');
      if (mainImg) {
        mainImg.style.opacity = '0';
        mainImg.src = btn.dataset.full;
        mainImg.onload = function(){ mainImg.style.opacity = '1'; };
      }
    });
  });
})();
</script>

<?php endwhile; get_footer(); ?>
