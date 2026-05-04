<?php
/**
 * Chargeurie — single-product.php v7
 * Prix soignés + sync variation→galerie/prix/sticky
 */
get_header();
while ( have_posts() ) : the_post();
global $product;

$image_id   = $product->get_image_id();
$gallery    = $product->get_gallery_image_ids();
$short_desc = $product->get_short_description();
$long_desc  = $product->get_description();
$attributes = array_filter( $product->get_attributes(), fn($a) => $a->get_visible() );
$rating_cnt = $product->get_rating_count();
$avg        = (float) $product->get_average_rating();
$badge      = function_exists('chg_card_badge') ? chg_card_badge($product) : null;

// Toutes les images : principale + galerie
$all_images = [];
if ( $image_id ) $all_images[] = $image_id;
foreach ( $gallery as $gid ) $all_images[] = $gid;

// Prix formaté pour la sticky bar (prix de base avant variation)
$base_price_html = $product->get_price_html();
?>

<div class="sp-page">

<!-- HERO ---------------------------------------------------------------- -->
<section class="sp-hero">
  <div class="sp-wrap">

    <!-- GALERIE -->
    <div class="sp-gallery">
      <div class="sp-main-frame" id="spFrame">
        <?php if ( $badge ) : ?>
          <span class="sp-badge badge-<?php echo esc_attr($badge['id']); ?>"><?php echo $badge['label']; ?></span>
        <?php endif; ?>
        <?php if ( $image_id ) :
          echo wp_get_attachment_image( $image_id, 'large', false, [
            'id'            => 'spMainImg',
            'class'         => 'sp-main-img',
            'loading'       => 'eager',
            'fetchpriority' => 'high',
          ]);
        else : ?>
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
        <?php foreach ( $all_images as $i => $img_id ) : ?>
          <button
            class="sp-thumb <?php echo $i === 0 ? 'is-active' : ''; ?>"
            role="tab"
            aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
            data-full="<?php echo esc_url( wp_get_attachment_image_url( $img_id, 'large' ) ); ?>"
            aria-label="Image <?php echo esc_attr($i + 1); ?>">
            <?php echo wp_get_attachment_image( $img_id, [72,72], false, ['loading'=>'lazy'] ); ?>
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
      <div class="sp-stars-row" aria-label="Note : <?php echo esc_attr(round($avg,1)); ?>/5 (<?php echo esc_attr($rating_cnt); ?> avis)">
        <span class="sp-stars" aria-hidden="true"><?php
          for ( $s = 1; $s <= 5; $s++ ) :
            if      ( $s <= floor($avg)    ) echo '<svg class="star star-on"   viewBox="0 0 16 16"><path d="M8 1l1.85 3.74L14 5.68l-3 2.92.7 4.12L8 10.77l-3.7 1.95L5 8.6 2 5.68l4.15-.94z" fill="currentColor"/></svg>';
            elseif  ( $s <= $avg + 0.5     ) echo '<svg class="star star-half" viewBox="0 0 16 16"><path d="M8 1v9.77l-3.7 1.95L5 8.6 2 5.68l4.15-.94z" fill="currentColor"/><path d="M8 1l1.85 3.74L14 5.68l-3 2.92.7 4.12L8 10.77z" fill="none" stroke="currentColor" stroke-width=".8"/></svg>';
            else                             echo '<svg class="star star-off"  viewBox="0 0 16 16"><path d="M8 1l1.85 3.74L14 5.68l-3 2.92.7 4.12L8 10.77l-3.7 1.95L5 8.6 2 5.68l4.15-.94z" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
          endfor;
        ?></span>
        <span class="sp-review-count"><?php echo esc_html($rating_cnt); ?> avis</span>
      </div>
      <?php endif; ?>

      <!-- BLOC PRIX (produit de base) -->
      <div class="sp-price-wrap" id="spPriceWrap">
        <?php echo $product->get_price_html(); ?>
      </div>

      <?php if ( $short_desc ) : ?>
        <div class="sp-short"><?php echo wp_kses_post($short_desc); ?></div>
      <?php endif; ?>

      <!-- Formulaire WooCommerce (variations + quantité + bouton) -->
      <div class="sp-form" id="spForm">
        <?php woocommerce_template_single_add_to_cart(); ?>
      </div>

      <!-- Garanties -->
      <ul class="sp-guarantees">
        <li>
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="4" width="12" height="10" rx="2"/>
            <path d="M14 8h2.5a1 1 0 011 1v4a2 2 0 01-4 0V9a1 1 0 01.5-.87z"/>
          </svg>
          <span>Livraison offerte dès <strong>35 €</strong></span>
        </li>
        <li>
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M17 4l-1.5 12H4.5L3 4"/><path d="M1 4h18M8 4V2h4v2"/>
          </svg>
          <span>Retours gratuits <strong>30 jours</strong></span>
        </li>
        <li>
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M10 18s7-4 7-9V4l-7-2-7 2v5c0 5 7 9 7 9z"/>
          </svg>
          <span>Garantie <strong>2 ans</strong></span>
        </li>
        <li>
          <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="1" y="5" width="18" height="12" rx="2"/>
            <line x1="1" y1="9" x2="19" y2="9"/>
          </svg>
          <span>CB &middot; PayPal &middot; Apple Pay</span>
        </li>
      </ul>

    </div><!-- /sp-buy -->
  </div><!-- /sp-wrap -->
</section>

<!-- CARACTÉRISTIQUES ---------------------------------------------------- -->
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
        <div class="sp-spec">
          <dt><?php echo esc_html($label); ?></dt>
          <dd><?php echo esc_html($val); ?></dd>
        </div>
      <?php endforeach; ?>
    </dl>
  </div>
</section>
<?php endif; ?>

<!-- DESCRIPTION + FAQ --------------------------------------------------- -->
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
      <?php if (!$long_desc) : ?><span class="sp-tag">Infos pratiques</span><?php endif; ?>
      <div class="sp-acc-list">
        <div class="sp-acc">
          <button class="sp-acc-btn" aria-expanded="false" aria-controls="acc-compat">
            Compatibilité
            <svg class="sp-acc-arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6l4 4 4-4"/></svg>
          </button>
          <div id="acc-compat" class="sp-acc-panel" hidden>Compatible avec tous les appareils USB-C : iPhone 15+, Samsung Galaxy, Google Pixel, MacBook Air/Pro, iPad Pro et tout appareil USB-C.</div>
        </div>
        <div class="sp-acc">
          <button class="sp-acc-btn" aria-expanded="false" aria-controls="acc-livraison">
            Livraison &amp; Retours
            <svg class="sp-acc-arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6l4 4 4-4"/></svg>
          </button>
          <div id="acc-livraison" class="sp-acc-panel" hidden>Expédition 24 h ouvrable. Livraison offerte dès 35 €, reçue en 3–4 jours. Retours acceptés sous 30 jours dans l’emballage d’origine.</div>
        </div>
        <div class="sp-acc">
          <button class="sp-acc-btn" aria-expanded="false" aria-controls="acc-garantie">
            Garantie
            <svg class="sp-acc-arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6l4 4 4-4"/></svg>
          </button>
          <div id="acc-garantie" class="sp-acc-panel" hidden>Garantie constructeur 2 ans. Retour ou échange sans condition dans les 30 premiers jours.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PRODUITS ASSOCIÉS --------------------------------------------------- -->
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
        if ( !$rp || !$rp->is_visible() ) continue;
        $rb  = function_exists('chg_card_badge') ? chg_card_badge($rp) : null;
        $rtid = $rp->get_image_id();
      ?>
        <article class="product-card" data-product-id="<?php echo esc_attr($rid); ?>">
          <a class="card-img-wrap" href="<?php echo esc_url(get_permalink($rid)); ?>" tabindex="-1" aria-hidden="true">
            <?php if ($rb) : ?><div class="card-badge badge-<?php echo esc_attr($rb['id']); ?>"><?php echo $rb['label']; ?></div><?php endif; ?>
            <?php if ($rtid) : echo wp_get_attachment_image($rtid,'chg-product-card',false,['class'=>'slide-product-img','loading'=>'lazy']);
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

<!-- STICKY ATC bar ------------------------------------------------------ -->
<div class="sp-sticky" id="spSticky" aria-hidden="true">
  <div class="sp-sticky-in">
    <?php if ( $image_id ) :
      echo wp_get_attachment_image($image_id, [44,44], false, [
        'class'   => 'sp-sticky-img',
        'id'      => 'spStickyImg',
        'loading' => 'lazy',
      ]);
    endif; ?>
    <span class="sp-sticky-name"><?php the_title(); ?></span>
    <span class="sp-sticky-price" id="spStickyPrice"><?php echo $base_price_html; ?></span>
    <button class="sp-sticky-btn" id="spStickyBtn" type="button">Ajouter au panier</button>
  </div>
</div>

<!-- =====================================================================
     JAVASCRIPT — Galerie + Variations + Sticky
     WooCommerce déclenche les événements jQuery "found_variation" et
     "reset_data" sur le formulaire .variations_form.
     Il faut être dans jQuery(document).ready() pour les écouter.
====================================================================== -->
<script>
jQuery(function($){

  /* ---- éléments DOM ---- */
  var $form    = $('form.variations_form');
  var mainImg  = document.getElementById('spMainImg');
  var $thumbs  = $('.sp-thumb');
  var $priceWrap  = $('#spPriceWrap');
  var $stickyPrice = $('#spStickyPrice');
  var $stickyImg   = $('#spStickyImg');
  var $spForm      = $('#spForm');
  var $sticky      = $('#spSticky');
  var $stickyBtn   = $('#spStickyBtn');

  /* Prix de base (produit simple ou "A partir de" d'un variable) */
  var basePriceHTML = $priceWrap.html();

  /* ---------------------------------------------------------------
     1. GALERIE : swap au clic sur miniature
  --------------------------------------------------------------- */
  function swapMainImg(src, thumbBtn) {
    if (!mainImg || !src) return;
    mainImg.style.opacity = '0';
    mainImg.src = src;
    mainImg.onload = function() { mainImg.style.opacity = '1'; };
    if (thumbBtn) {
      $thumbs.removeClass('is-active').attr('aria-selected','false');
      $(thumbBtn).addClass('is-active').attr('aria-selected','true');
    }
  }

  $thumbs.on('click', function() {
    swapMainImg($(this).data('full'), this);
  });

  /* ---------------------------------------------------------------
     2. PRIX : helpers pour extraire et afficher
  --------------------------------------------------------------- */
  /**
   * Parse un prix WooCommerce (HTML) et retourne le montant float.
   * Cherche .woocommerce-Price-amount ou <bdi> en dernier.
   */
  function extractPrice(html) {
    var tmp = document.createElement('div');
    tmp.innerHTML = html;
    /* Prix dans <ins> (promo) ou montant simple */
    var el = tmp.querySelector('ins .woocommerce-Price-amount bdi') ||
             tmp.querySelector('.woocommerce-Price-amount bdi') ||
             tmp.querySelector('bdi');
    if (!el) return null;
    var txt = el.textContent.replace(/[^0-9,\.]/g,'').replace(',','.');
    return parseFloat(txt) || null;
  }

  /**
   * Ajoute un badge "Économie X €" si on compare ancien/nouveau prix.
   */
  function maybeAddSaveBadge(html, variation) {
    if (!variation) return html;
    var reg = parseFloat(variation.display_regular_price) || 0;
    var cur = parseFloat(variation.display_price)         || 0;
    if (reg > cur && cur > 0) {
      var save = Math.round(reg - cur);
      html += ' <span class="sp-price-save">− ' + save + ' €</span>';
    }
    return html;
  }

  /* ---------------------------------------------------------------
     3. ÉVÉNEMENTS VARIATION WooCommerce
  --------------------------------------------------------------- */
  if ($form.length) {

    /* Variation trouvée : met à jour image + prix + sticky */
    $form.on('found_variation', function(e, variation) {

      /* 3a. Image */
      if (variation.image && variation.image.full_src && variation.image.full_src.length > 0) {
        swapMainImg(variation.image.full_src, null);
        /* On dé-sélectionne toutes les miniatures (image vient de la variation) */
        $thumbs.removeClass('is-active').attr('aria-selected','false');
        /* Mise à jour image sticky */
        if ($stickyImg.length && variation.image.thumb_src) {
          $stickyImg.attr('src', variation.image.thumb_src);
        }
      }

      /* 3b. Prix dans le panneau achat :
         WooCommerce injecte .woocommerce-variation-price automatiquement,
         mais on personalise aussi #spPriceWrap si le prix de variation
         n'est pas affiché via ce bloc standard. */
      var varPriceEl = $form.find('.woocommerce-variation-price');
      if (varPriceEl.length && varPriceEl.find('.price').length) {
        /* WC a injecté le prix de variation, on laisse faire WC
           et on ajoute juste le badge économie */
        var vp = varPriceEl.find('.price');
        /* Retire ancien badge */
        varPriceEl.find('.sp-price-save').remove();
        var reg = parseFloat(variation.display_regular_price) || 0;
        var cur = parseFloat(variation.display_price)         || 0;
        if (reg > cur && cur > 0) {
          vp.after('<span class="sp-price-save">− ' + Math.round(reg - cur) + ' €</span>');
        }
        /* Masquer le prix de base */
        $priceWrap.hide();
      } else {
        /* Fallback : afficher le prix WooCommerce depuis les données variation */
        $priceWrap.show();
        if (variation.price_html && variation.price_html.length > 0) {
          $priceWrap.html(maybeAddSaveBadge(variation.price_html, variation));
        }
      }

      /* 3c. Prix sticky */
      if ($stickyPrice.length && variation.price_html) {
        $stickyPrice.html(variation.price_html);
      }
    });

    /* Reset (désélection variation) : retour à l'état initial */
    $form.on('reset_data', function() {

      /* Image : revenir à la première miniature */
      var firstThumb = $thumbs.first();
      if (firstThumb.length && mainImg) {
        swapMainImg(firstThumb.data('full'), firstThumb[0]);
      }
      /* Sticky image */
      var firstSrc = firstThumb.length ? firstThumb.find('img').attr('src') : null;
      if ($stickyImg.length && firstSrc) $stickyImg.attr('src', firstSrc);

      /* Prix : revenir au prix de base */
      $priceWrap.show().html(basePriceHTML);
      if ($stickyPrice.length) $stickyPrice.html(basePriceHTML);
    });
  }

  /* ---------------------------------------------------------------
     4. STICKY ATC BAR (IntersectionObserver)
  --------------------------------------------------------------- */
  if ($spForm.length && $sticky.length) {
    var io = new IntersectionObserver(function(entries) {
      var visible = !entries[0].isIntersecting;
      $sticky.toggleClass('is-visible', visible);
      $sticky.attr('aria-hidden', visible ? 'false' : 'true');
    }, { threshold: 0.1 });
    io.observe($spForm[0]);
  }

  if ($stickyBtn.length) {
    $stickyBtn.on('click', function() {
      var real = $spForm.find('.single_add_to_cart_button:not(.disabled), button[type="submit"]:not(:disabled)').first();
      if (real.length) {
        real.trigger('click');
      } else {
        $spForm[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  }

  /* ---------------------------------------------------------------
     5. ACCORDIONS (FAQ)
  --------------------------------------------------------------- */
  $('.sp-acc-btn').on('click', function() {
    var $btn   = $(this);
    var open   = $btn.attr('aria-expanded') === 'true';
    var $panel = $('#' + $btn.attr('aria-controls'));
    if (!$panel.length) return;

    /* Fermer les autres */
    $('.sp-acc-btn[aria-expanded="true"]').not($btn).each(function() {
      var $ob = $(this);
      var $op = $('#' + $ob.attr('aria-controls'));
      $ob.attr('aria-expanded','false');
      $op.css({ height: $op[0].scrollHeight + 'px', overflow:'hidden' });
      requestAnimationFrame(function() {
        $op.css({ transition:'height .3s ease, opacity .25s', height:'0', opacity:'0' });
        $op.one('transitionend', function() { $op.prop('hidden',true).css('',''); });
      });
    });

    if (open) {
      $btn.attr('aria-expanded','false');
      $panel.css({ height: $panel[0].scrollHeight + 'px', overflow:'hidden' });
      requestAnimationFrame(function() {
        $panel.css({ transition:'height .3s ease, opacity .25s', height:'0', opacity:'0' });
        $panel.one('transitionend', function() { $panel.prop('hidden',true).css('',''); });
      });
    } else {
      $btn.attr('aria-expanded','true');
      $panel.prop('hidden',false).css({ height:'0', opacity:'0', overflow:'hidden' });
      var h = $panel[0].scrollHeight;
      requestAnimationFrame(function() {
        $panel.css({ transition:'height .3s ease, opacity .25s', height: h+'px', opacity:'1' });
        $panel.one('transitionend', function() { $panel.css({ height:'', overflow:'' }); });
      });
    }
  });

}); /* end jQuery ready */
</script>

<?php endwhile; get_footer(); ?>
