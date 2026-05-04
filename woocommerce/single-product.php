<?php
/**
 * Chargeurie — single-product.php v10
 * found_variation sur $(document) — fix image swap
 */
get_header();
while ( have_posts() ) : the_post();
global $product;

$image_id    = $product->get_image_id();
$gallery     = $product->get_gallery_image_ids();
$short_desc  = $product->get_short_description();
$long_desc   = $product->get_description();
$attributes  = array_filter( $product->get_attributes(), fn($a) => $a->get_visible() );
$rating_cnt  = $product->get_rating_count();
$avg         = (float) $product->get_average_rating();
$badge       = function_exists('chg_card_badge') ? chg_card_badge($product) : null;
$is_variable = $product->is_type('variable');

$all_images = [];
if ( $image_id ) $all_images[] = $image_id;
foreach ( $gallery as $gid ) $all_images[] = $gid;

$base_price_html = $product->get_price_html();
$main_img_url    = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';
?>

<div class="sp-page">

<section class="sp-hero">
  <div class="sp-wrap">

    <!-- GALERIE -->
    <div class="sp-gallery">
      <div class="sp-main-frame" id="spFrame">
        <?php if ( $badge ) : ?>
          <span class="sp-badge badge-<?php echo esc_attr($badge['id']); ?>"><?php echo $badge['label']; ?></span>
        <?php endif; ?>

        <?php if ( $image_id ) :
          // On génère l'image SANS srcset (sizes) pour éviter que le navigateur
          // choisisse une autre source que celle qu'on set en JS
          $img_src = wp_get_attachment_image_url( $image_id, 'large' );
        ?>
          <img
            id="spMainImg"
            class="sp-main-img"
            src="<?php echo esc_url($img_src); ?>"
            alt="<?php echo esc_attr(get_the_title()); ?>"
            width="600" height="600"
            loading="eager" fetchpriority="high"
          >
        <?php else : ?>
          <div class="sp-no-img" aria-hidden="true">
            <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1">
              <rect x="4" y="4" width="40" height="40" rx="4"/>
              <circle cx="17" cy="18" r="4"/>
              <path d="M4 34l11-11 8 8 7-7 14 14"/>
            </svg>
          </div>
        <?php endif; ?>
      </div>

      <?php if ( count($all_images) > 1 ) : ?>
      <div class="sp-thumbs" role="tablist" aria-label="Vues">
        <?php foreach ( $all_images as $i => $img_id ) :
          $full_url  = wp_get_attachment_image_url( $img_id, 'large' );
          $thumb_url = wp_get_attachment_image_url( $img_id, 'thumbnail' );
        ?>
          <button
            class="sp-thumb <?php echo $i === 0 ? 'is-active' : ''; ?>"
            role="tab"
            aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
            data-full="<?php echo esc_url($full_url); ?>"
            data-thumb="<?php echo esc_url($thumb_url); ?>"
            aria-label="Image <?php echo esc_attr($i + 1); ?>">
            <img src="<?php echo esc_url($thumb_url); ?>" alt="" width="68" height="68" loading="lazy">
          </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div><!-- /sp-gallery -->

    <!-- PANNEAU ACHAT -->
    <div class="sp-buy">

      <nav aria-label="Fil d’Ariane" class="sp-crumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a>
        <span aria-hidden="true">/</span>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Boutique</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page"><?php the_title(); ?></span>
      </nav>

      <h1 class="sp-title"><?php the_title(); ?></h1>

      <?php if ( wc_reviews_enabled() && $rating_cnt > 0 ) : ?>
      <div class="sp-stars-row">
        <span class="sp-stars" aria-hidden="true"><?php
          for ( $s = 1; $s <= 5; $s++ ) :
            if     ( $s <= floor($avg) ) echo '<svg class="star star-on" viewBox="0 0 16 16"><path d="M8 1l1.85 3.74L14 5.68l-3 2.92.7 4.12L8 10.77l-3.7 1.95L5 8.6 2 5.68l4.15-.94z" fill="currentColor"/></svg>';
            elseif ( $s <= $avg + 0.5 ) echo '<svg class="star star-half" viewBox="0 0 16 16"><path d="M8 1v9.77l-3.7 1.95L5 8.6 2 5.68l4.15-.94z" fill="currentColor"/></svg>';
            else                        echo '<svg class="star star-off" viewBox="0 0 16 16"><path d="M8 1l1.85 3.74L14 5.68l-3 2.92.7 4.12L8 10.77l-3.7 1.95L5 8.6 2 5.68l4.15-.94z" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
          endfor;
        ?></span>
        <span class="sp-review-count"><?php echo esc_html($rating_cnt); ?> avis</span>
      </div>
      <?php endif; ?>

      <div id="spPriceBase"><?php echo $base_price_html; ?></div>
      <div id="spPriceVariant" style="display:none" aria-live="polite"></div>

      <?php if ( $short_desc ) : ?>
        <div class="sp-short"><?php echo wp_kses_post($short_desc); ?></div>
      <?php endif; ?>

      <div class="sp-form" id="spForm">
        <?php woocommerce_template_single_add_to_cart(); ?>
      </div>

      <ul class="sp-guarantees">
        <li><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="12" height="10" rx="2"/><path d="M14 8h2.5a1 1 0 011 1v4a2 2 0 01-4 0V9a1 1 0 01.5-.87z"/></svg><span>Livraison offerte dès <strong>35 €</strong></span></li>
        <li><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 4l-1.5 12H4.5L3 4"/><path d="M1 4h18M8 4V2h4v2"/></svg><span>Retours gratuits <strong>30 jours</strong></span></li>
        <li><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10 18s7-4 7-9V4l-7-2-7 2v5c0 5 7 9 7 9z"/></svg><span>Garantie <strong>2 ans</strong></span></li>
        <li><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="5" width="18" height="12" rx="2"/><line x1="1" y1="9" x2="19" y2="9"/></svg><span>CB &middot; PayPal &middot; Apple Pay</span></li>
      </ul>

    </div><!-- /sp-buy -->
  </div><!-- /sp-wrap -->
</section>

<!-- CARACTÉRISTIQUES ------------------------------------------------------ -->
<?php if ( ! empty($attributes) ) : ?>
<section class="sp-specs">
  <div class="sp-container">
    <div class="sp-section-head">
      <span class="sp-tag">Fiche technique</span>
      <h2 class="sp-section-title">Caractéristiques</h2>
    </div>
    <dl class="sp-specs-grid">
      <?php foreach ( $attributes as $attribute ) :
        $label = wc_attribute_label( $attribute->get_name(), $product );
        if ( $attribute->is_taxonomy() ) {
          $terms = wp_get_post_terms( $product->get_id(), $attribute->get_name(), ['fields'=>'names'] );
          $val   = (!is_wp_error($terms) && !empty($terms)) ? implode(', ', $terms) : '';
        } else {
          $opts = $attribute->get_options();
          $val  = !empty($opts) ? implode(', ', $opts) : '';
        }
        if (!$val) continue;
      ?>
        <div class="sp-spec"><dt><?php echo esc_html($label); ?></dt><dd><?php echo esc_html($val); ?></dd></div>
      <?php endforeach; ?>
    </dl>
  </div>
</section>
<?php endif; ?>

<!-- DESCRIPTION + FAQ ----------------------------------------------------- -->
<section class="sp-details">
  <div class="sp-container sp-details-inner">
    <?php if ( $long_desc ) : ?>
    <div class="sp-desc">
      <span class="sp-tag">Description</span>
      <h2 class="sp-section-title sp-section-title--sm">Détails</h2>
      <div class="sp-desc-body"><?php echo wp_kses_post($long_desc); ?></div>
    </div>
    <?php endif; ?>
    <div class="sp-faq <?php echo $long_desc ? '' : 'sp-faq--full'; ?>">
      <div class="sp-acc-list">
        <div class="sp-acc">
          <button class="sp-acc-btn" aria-expanded="false" aria-controls="acc-compat">Compatibilité<svg class="sp-acc-arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6l4 4 4-4"/></svg></button>
          <div id="acc-compat" class="sp-acc-panel" hidden>Compatible avec tous les appareils USB-C : iPhone 15+, Samsung Galaxy, Google Pixel, MacBook Air/Pro, iPad Pro.</div>
        </div>
        <div class="sp-acc">
          <button class="sp-acc-btn" aria-expanded="false" aria-controls="acc-livraison">Livraison &amp; Retours<svg class="sp-acc-arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6l4 4 4-4"/></svg></button>
          <div id="acc-livraison" class="sp-acc-panel" hidden>Expédition 24 h ouvrable. Livraison offerte dès 35 €, reçue en 3–4 jours. Retours acceptés sous 30 jours.</div>
        </div>
        <div class="sp-acc">
          <button class="sp-acc-btn" aria-expanded="false" aria-controls="acc-garantie">Garantie<svg class="sp-acc-arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6l4 4 4-4"/></svg></button>
          <div id="acc-garantie" class="sp-acc-panel" hidden>Garantie constructeur 2 ans. Retour ou échange sans condition dans les 30 premiers jours.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PRODUITS ASSOCIÉS ---------------------------------------------------- -->
<?php
$related = wc_get_related_products( $product->get_id(), 3 );
if ( !empty($related) ) :
?>
<section class="sp-related">
  <div class="sp-container">
    <div class="sp-section-head">
      <span class="sp-tag">Vous aimerez aussi</span>
      <h2 class="sp-section-title">Dans la gamme</h2>
    </div>
    <div class="products-grid">
      <?php foreach ( $related as $rid ) :
        $rp = wc_get_product($rid);
        if (!$rp || !$rp->is_visible()) continue;
        $rb  = function_exists('chg_card_badge') ? chg_card_badge($rp) : null;
        $rtid = $rp->get_image_id();
      ?>
        <article class="product-card" data-product-id="<?php echo esc_attr($rid); ?>">
          <a class="card-img-wrap" href="<?php echo esc_url(get_permalink($rid)); ?>" tabindex="-1" aria-hidden="true">
            <?php if ($rb) : ?><div class="card-badge badge-<?php echo esc_attr($rb['id']); ?>"><?php echo $rb['label']; ?></div><?php endif; ?>
            <?php if ($rtid) echo wp_get_attachment_image($rtid,'chg-product-card',false,['class'=>'slide-product-img','loading'=>'lazy']);
            else : ?><div class="card-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width=".8" width="36" height="36"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div><?php endif; ?>
          </a>
          <div class="card-info">
            <h3 class="card-name"><a href="<?php echo esc_url(get_permalink($rid)); ?>"><?php echo esc_html($rp->get_name()); ?></a></h3>
            <div class="card-bottom">
              <div class="card-price"><?php echo $rp->get_price_html(); ?></div>
              <a href="<?php echo esc_url(get_permalink($rid)); ?>" class="card-add">Voir</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

</div><!-- /sp-page -->

<!-- STICKY BAR ----------------------------------------------------------- -->
<div class="sp-sticky" id="spSticky" aria-hidden="true">
  <div class="sp-sticky-in">
    <?php if ($image_id) echo wp_get_attachment_image($image_id,[44,44],false,['class'=>'sp-sticky-img','id'=>'spStickyImg','loading'=>'lazy']); ?>
    <span class="sp-sticky-name"><?php the_title(); ?></span>
    <span class="sp-sticky-price" id="spStickyPrice"><?php echo $base_price_html; ?></span>
    <button class="sp-sticky-btn" id="spStickyBtn" type="button">Ajouter au panier</button>
  </div>
</div>

<script>
(function($){
  'use strict';

  // On attend que WooCommerce soit prêt
  $(document).ready(function() {

    var imgEl        = document.getElementById('spMainImg');
    var $thumbs      = $('.sp-thumb');
    var $priceBase   = $('#spPriceBase');
    var $priceVar    = $('#spPriceVariant');
    var $stickyPrice = $('#spStickyPrice');
    var $stickyImg   = $('#spStickyImg');
    var $spFormWrap  = $('#spForm');
    var $sticky      = $('#spSticky');
    var $stickyBtn   = $('#spStickyBtn');

    var baseImgSrc    = <?php echo json_encode( (string)$main_img_url ); ?>;
    var basePriceHTML = $priceBase.html();

    /* -----------------------------------------------------------
       SWAP IMAGE : remplace src ET supprime srcset/sizes
       pour que le navigateur n’override pas avec srcset
    ----------------------------------------------------------- */
    function swapImg(src, $activeThumb) {
      if (!src || !imgEl) return;
      imgEl.style.opacity = '0';
      imgEl.removeAttribute('srcset');
      imgEl.removeAttribute('sizes');
      imgEl.src = src;
      imgEl.onload = function() {
        imgEl.style.opacity = '1';
        imgEl.onload = null;
      };
      $thumbs.removeClass('is-active').attr('aria-selected', 'false');
      if ($activeThumb && $activeThumb.length) {
        $activeThumb.addClass('is-active').attr('aria-selected', 'true');
      }
    }

    /* Clic miniature */
    $thumbs.on('click', function() {
      swapImg($(this).data('full'), $(this));
    });

    /* -----------------------------------------------------------
       ÉCOUTE SUR $(document) — c’est le bon pattern WooCommerce
       WC déclenche found_variation et reset_data sur le document
    ----------------------------------------------------------- */
    $(document).on('found_variation.chg', function(e, variation) {
      console.log('[CHG] found_variation:', variation);

      /* IMAGE — WooCommerce 8+ : variation.image est un objet avec :
         url, full_src, full_src_w, full_src_h, src, src_w, src_h,
         thumb_src, thumb_src_w, thumb_src_h, srcset, sizes,
         image_id, alt, title, caption, description, data
      */
      var img = variation.image || {};
      console.log('[CHG] image object:', img);

      var newSrc = img.url        // WC 8+
                || img.full_src   // WC < 8
                || img.src        // fallback
                || '';

      // On ne change l'image que si WC en fournit une différente
      // et que ce n'est pas juste l'URL courante de la page
      if (newSrc && newSrc !== window.location.href && newSrc !== baseImgSrc) {
        swapImg(newSrc, null);
        if ($stickyImg.length) {
          $stickyImg.attr('src', img.thumb_src || img.src || newSrc);
        }
      } else if (newSrc === baseImgSrc) {
        // Variation utilise la même image que le produit parent — OK, pas de swap nécessaire
        console.log('[CHG] image identique au produit parent, pas de swap.');
      }

      /* PRIX */
      var priceHtml = variation.price_html || '';
      if (priceHtml) {
        $priceBase.hide();
        $priceVar.html(priceHtml).show();
        $stickyPrice.html(priceHtml);
      }
    });

    $(document).on('reset_data.chg', function() {
      console.log('[CHG] reset_data');
      /* Image : revenir à l’image du produit parent */
      var $first = $thumbs.first();
      swapImg(baseImgSrc || $first.data('full') || '', $first.length ? $first : null);
      if ($stickyImg.length && $first.length) {
        $stickyImg.attr('src', $first.data('thumb') || '');
      }
      /* Prix */
      $priceVar.hide().empty();
      $priceBase.show().html(basePriceHTML);
      $stickyPrice.html(basePriceHTML);
    });

    /* -----------------------------------------------------------
       STICKY BAR
    ----------------------------------------------------------- */
    if ($spFormWrap.length && $sticky.length) {
      var io = new IntersectionObserver(function(entries) {
        var hidden = !entries[0].isIntersecting;
        $sticky.toggleClass('is-visible', hidden).attr('aria-hidden', hidden ? 'false' : 'true');
      }, { threshold: 0.1 });
      io.observe($spFormWrap[0]);
    }

    $stickyBtn.on('click', function() {
      var $btn = $spFormWrap.find('.single_add_to_cart_button:not(.disabled)').first();
      if ($btn.length) $btn.trigger('click');
      else if ($spFormWrap[0]) $spFormWrap[0].scrollIntoView({ behavior:'smooth', block:'center' });
    });

    /* -----------------------------------------------------------
       ACCORDIONS
    ----------------------------------------------------------- */
    $('.sp-acc-btn').on('click', function() {
      var $btn   = $(this);
      var isOpen = $btn.attr('aria-expanded') === 'true';
      var $panel = $('#' + $btn.attr('aria-controls'));
      if (!$panel.length) return;

      $('.sp-acc-btn[aria-expanded="true"]').not($btn).each(function() {
        var $ob = $(this), $op = $('#' + $ob.attr('aria-controls'));
        $ob.attr('aria-expanded','false');
        $op.css({ height:$op[0].scrollHeight+'px', overflow:'hidden' });
        requestAnimationFrame(function(){
          $op.css({ transition:'height .3s ease, opacity .25s', height:'0', opacity:'0' });
          $op.one('transitionend', function(){ $op.prop('hidden',true).css('',''); });
        });
      });

      if (isOpen) {
        $btn.attr('aria-expanded','false');
        $panel.css({ height:$panel[0].scrollHeight+'px', overflow:'hidden' });
        requestAnimationFrame(function(){
          $panel.css({ transition:'height .3s ease, opacity .25s', height:'0', opacity:'0' });
          $panel.one('transitionend', function(){ $panel.prop('hidden',true).css('',''); });
        });
      } else {
        $btn.attr('aria-expanded','true');
        $panel.prop('hidden',false).css({ height:'0', opacity:'0', overflow:'hidden' });
        var h = $panel[0].scrollHeight;
        requestAnimationFrame(function(){
          $panel.css({ transition:'height .3s ease, opacity .25s', height:h+'px', opacity:'1' });
          $panel.one('transitionend', function(){ $panel.css({ height:'', overflow:'' }); });
        });
      }
    });

  }); // ready

})(jQuery);
</script>

<?php endwhile; get_footer(); ?>
