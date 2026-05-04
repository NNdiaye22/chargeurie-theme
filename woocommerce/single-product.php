<?php
/**
 * Chargeurie — woocommerce/single-product.php
 * Fiche produit premium v4 — style page d’accueil
 */
get_header();
while ( have_posts() ) :
  the_post();
  global $product;

  $badge      = function_exists('chg_card_badge') ? chg_card_badge($product) : null;
  $image_id   = $product->get_image_id();
  $gallery    = $product->get_gallery_image_ids();
  $short      = $product->get_short_description();
  $desc       = $product->get_description();
  $sp_attributes = array_filter( $product->get_attributes(), fn($a) => $a->get_visible() );
?>

<main class="chg-single-product">

  <!-- ═══════════════════════════════════════════════
     HERO : Galerie sombre + Infos
  ═══════════════════════════════════════════════ -->
  <section class="sp-hero">
    <div class="sp-hero-inner">

      <!-- Galerie -->
      <div class="sp-gallery">
        <div class="sp-img-main">
          <?php if ($badge) : ?>
            <div class="sp-badge badge-<?php echo esc_attr($badge['id']); ?>"><?php echo $badge['label']; ?></div>
          <?php endif; ?>
          <?php if ($image_id) : ?>
            <?php echo wp_get_attachment_image($image_id, 'chg-product-featured', false, ['class' => 'sp-main-img', 'id' => 'spMainImg']); ?>
          <?php else : ?>
            <div class="sp-img-placeholder">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M8 1.5L2.5 8H7L5.5 12.5L12 6H7.5L8 1.5Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round" opacity=".2"/></svg>
            </div>
          <?php endif; ?>
        </div>

        <?php if (!empty($gallery)) : ?>
          <div class="sp-thumbs" id="spThumbs">
            <?php if ($image_id) : ?>
              <button class="sp-thumb active"
                data-full="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'chg-product-featured')); ?>"
                aria-label="Image principale">
                <?php echo wp_get_attachment_image($image_id, [80,80]); ?>
              </button>
            <?php endif; ?>
            <?php foreach ($gallery as $gid) : ?>
              <button class="sp-thumb"
                data-full="<?php echo esc_url(wp_get_attachment_image_url($gid, 'chg-product-featured')); ?>"
                aria-label="Vue alternative">
                <?php echo wp_get_attachment_image($gid, [80,80]); ?>
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Colonne droite -->
      <div class="sp-summary">

        <nav class="sp-breadcrumb" aria-label="Fil d'Ariane">
          <a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a>
          <span aria-hidden="true">&rsaquo;</span>
          <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Boutique</a>
          <span aria-hidden="true">&rsaquo;</span>
          <span><?php the_title(); ?></span>
        </nav>

        <p class="sp-eyebrow">Chargeurie &mdash; Chargeur Premium</p>
        <h1 class="sp-title"><?php the_title(); ?></h1>

        <div class="sp-price"><?php echo $product->get_price_html(); ?></div>

        <?php if ($short) : ?>
          <p class="sp-short-desc"><?php echo wp_kses_post($short); ?></p>
        <?php endif; ?>

        <!-- Form WC -->
        <div class="sp-form"><?php woocommerce_template_single_add_to_cart(); ?></div>

        <!-- Trust strip -->
        <ul class="sp-trust">
          <li>
            <span class="trust-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 14 14" fill="none"><path d="M1 9V5a1 1 0 011-1h6v5M1 9h8M1 9a1.5 1.5 0 003 0m5 0a1.5 1.5 0 003 0M12 9V7.5L10 5H8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <span>Livraison offerte dès <strong>35 €</strong> &middot; reçue en <strong>3&ndash;4 j</strong> ouvrés</span>
          </li>
          <li>
            <span class="trust-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 14 14" fill="none"><path d="M2 7A5 5 0 107 2H4m0 0L2 4m2-2L6 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <span>Retours sans frais sous <strong>30 jours</strong></span>
          </li>
          <li>
            <span class="trust-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 14 14" fill="none"><path d="M7 1L2 3V7c0 2.8 2.2 4.7 5 5.5C9.8 11.7 12 9.8 12 7V3L7 1Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
            </span>
            <span>Garantie <strong>2 ans</strong> constructeur</span>
          </li>
        </ul>

      </div><!-- /sp-summary -->
    </div><!-- /sp-hero-inner -->
  </section><!-- /sp-hero -->

  <!-- ═══════════════════════════════════════════════
     STATS PRODUIT (fond blanc, style .stats homepage)
  ═══════════════════════════════════════════════ -->
  <?php if ( ! empty( $sp_attributes ) ) : ?>
  <section class="sp-specs-band">
    <div class="sp-specs-inner chg-container">
      <p class="sp-specs-tag">Spécifications</p>
      <div class="sp-specs-grid">
        <?php foreach ( $sp_attributes as $attribute ) :
          $attr_name = wc_attribute_label( $attribute->get_name(), $product );
          if ( $attribute->is_taxonomy() ) {
            $terms = wp_get_post_terms( $product->get_id(), $attribute->get_name(), [ 'fields' => 'names' ] );
            $attr_value = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? implode( ', ', $terms ) : '';
          } else {
            $options    = $attribute->get_options();
            $attr_value = ! empty( $options ) ? implode( ', ', $options ) : '';
          }
          if ( ! $attr_value ) continue;
        ?>
          <div class="sp-spec-item">
            <span class="sp-spec-value"><?php echo esc_html( $attr_value ); ?></span>
            <span class="sp-spec-label"><?php echo esc_html( $attr_name ); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ═══════════════════════════════════════════════
     DESCRIPTION + ACCORDIONS (fond off, style manifesto)
  ═══════════════════════════════════════════════ -->
  <section class="sp-details-section">
    <div class="sp-details-inner chg-container">

      <?php if ($desc) : ?>
      <div class="sp-desc-block">
        <p class="sp-details-tag">Description</p>
        <div class="sp-desc-text"><?php echo wp_kses_post($desc); ?></div>
      </div>
      <?php endif; ?>

      <div class="sp-accordions">

        <details class="sp-accordion">
          <summary class="sp-accordion-title">Compatibilité</summary>
          <div class="sp-accordion-body">
            <p>Compatible avec tous les appareils USB-C : iPhone 15+, Samsung Galaxy, Google Pixel, MacBook, iPad Pro et tout appareil à port USB-C.</p>
          </div>
        </details>

        <details class="sp-accordion">
          <summary class="sp-accordion-title">Livraison &amp; Retours</summary>
          <div class="sp-accordion-body">
            <p>Expédition sous 24 h les jours ouvrables. Livraison offerte dès 35 €, reçue en 3 à 4 jours ouvrés. Retours acceptés sous 30 jours — produit non utilisé dans son emballage d’origine.</p>
          </div>
        </details>

        <details class="sp-accordion">
          <summary class="sp-accordion-title">Garantie</summary>
          <div class="sp-accordion-body">
            <p>Garantie constructeur 2 ans. En cas de défaut, échange ou remboursement intégral sans condition dans les 30 premiers jours.</p>
          </div>
        </details>

      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════════
     PRODUITS ASSOCIÉS
  ═══════════════════════════════════════════════ -->
  <?php
  $related = wc_get_related_products($product->get_id(), 3);
  if (!empty($related)) :
  ?>
    <section class="sp-related">
      <div class="sp-related-inner chg-container">
        <div class="sp-related-header">
          <p class="products-tag">Vous aimerez aussi</p>
          <h2 class="sp-related-title">Dans la même gamme</h2>
        </div>
        <div class="products-grid">
          <?php foreach ($related as $rid) :
            $rp = wc_get_product($rid);
            if (!$rp || !$rp->is_visible()) continue;
            $rb = function_exists('chg_card_badge') ? chg_card_badge($rp) : null;
          ?>
            <div class="product-card">
              <a href="<?php echo esc_url(get_permalink($rid)); ?>" class="card-img-wrap">
                <?php if ($rb) : ?><div class="card-badge badge-<?php echo esc_attr($rb['id']); ?>"><?php echo $rb['label']; ?></div><?php endif; ?>
                <?php $rtid = $rp->get_image_id(); ?>
                <?php if ($rtid) : echo wp_get_attachment_image($rtid, 'chg-product-card', false, ['class'=>'slide-product-img','loading'=>'lazy']);
                else : ?>
                  <div class="card-placeholder"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M8 1.5L2.5 8H7L5.5 12.5L12 6H7.5L8 1.5Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round" opacity=".15"/></svg></div>
                <?php endif; ?>
              </a>
              <div class="card-info">
                <div class="card-name"><?php echo esc_html($rp->get_name()); ?></div>
                <div class="card-bottom">
                  <div class="card-price"><?php echo $rp->get_price_html(); ?></div>
                  <a href="<?php echo esc_url(get_permalink($rid)); ?>" class="card-add">Voir</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

</main>

<script>
(function(){
  var mainImg = document.getElementById('spMainImg');
  var thumbs  = document.querySelectorAll('.sp-thumb');

  function setMainImg(src){
    if(!mainImg) return;
    mainImg.style.opacity='0';
    mainImg.src=src;
    mainImg.onload=function(){ mainImg.style.opacity='1'; };
  }

  thumbs.forEach(function(btn){
    btn.addEventListener('click',function(){
      thumbs.forEach(function(b){ b.classList.remove('active'); });
      btn.classList.add('active');
      setMainImg(btn.dataset.full);
    });
  });

  document.addEventListener('DOMContentLoaded',function(){
    var form=document.querySelector('form.variations_form');
    if(!form) return;
    jQuery(form).on('found_variation',function(e,variation){
      if(variation.image && variation.image.full_src && variation.image.full_src!==''){
        setMainImg(variation.image.full_src);
        thumbs.forEach(function(b){ b.classList.remove('active'); });
        var matched=Array.from(thumbs).find(function(b){ return b.dataset.full===variation.image.full_src; });
        if(matched) matched.classList.add('active');
      }
    });
    jQuery(form).on('reset_data',function(){
      var first=thumbs[0];
      if(first){
        thumbs.forEach(function(b){ b.classList.remove('active'); });
        first.classList.add('active');
        setMainImg(first.dataset.full);
      }
    });
  });
})();
</script>

<?php endwhile; get_footer(); ?>
