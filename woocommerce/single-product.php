<?php
/**
 * Chargeurie — woocommerce/single-product.php
 * Fiche produit premium v5 — conversion-first
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
  $attrs      = array_filter( $product->get_attributes(), fn($a) => $a->get_visible() );
  $rating_cnt = $product->get_rating_count();
  $avg_rating = $product->get_average_rating();
?>

<main class="chg-sp" data-sp>

  <!-- ── HERO : galerie (fond sombre) + résumé achat ──────────────────── -->
  <section class="sp-hero">
    <div class="sp-hero-inner chg-container-xl">

      <!-- Galerie (col gauche) -->
      <div class="sp-col-gallery" role="region" aria-label="Galerie produit">

        <div class="sp-img-wrap">
          <?php if ($badge) : ?>
            <span class="sp-badge badge-<?php echo esc_attr($badge['id']); ?>" aria-label="<?php echo esc_attr(strip_tags($badge['label'])); ?>">
              <?php echo $badge['label']; ?>
            </span>
          <?php endif; ?>

          <?php if ($image_id) : ?>
            <?php echo wp_get_attachment_image( $image_id, 'chg-product-featured', false, [
              'class'   => 'sp-main-img',
              'id'      => 'spMainImg',
              'loading' => 'eager',
              'fetchpriority' => 'high',
            ]); ?>
          <?php else : ?>
            <div class="sp-img-empty" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
          <?php endif; ?>
        </div>

        <?php if (!empty($gallery)) : ?>
          <div class="sp-thumbs" id="spThumbs" role="tablist" aria-label="Vues du produit">
            <?php if ($image_id) : ?>
              <button class="sp-thumb is-active"
                role="tab" aria-selected="true"
                data-full="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'chg-product-featured')); ?>"
                aria-label="Vue principale">
                <?php echo wp_get_attachment_image($image_id, [80,80], false, ['loading'=>'lazy']); ?>
              </button>
            <?php endif; ?>
            <?php foreach ($gallery as $i => $gid) : ?>
              <button class="sp-thumb"
                role="tab" aria-selected="false"
                data-full="<?php echo esc_url(wp_get_attachment_image_url($gid, 'chg-product-featured')); ?>"
                aria-label="Vue <?php echo esc_attr($i+2); ?>">
                <?php echo wp_get_attachment_image($gid, [80,80], false, ['loading'=>'lazy']); ?>
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div><!-- /sp-col-gallery -->

      <!-- Panneau achat (col droite) -->
      <div class="sp-col-buy">

        <!-- Breadcrumb -->
        <nav class="sp-breadcrumb" aria-label="Fil d'Ariane">
          <ol>
            <li><a href="<?php echo esc_url(home_url('/')); ?>">Accueil</a></li>
            <li aria-hidden="true">&rsaquo;</li>
            <li><a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Boutique</a></li>
            <li aria-hidden="true">&rsaquo;</li>
            <li aria-current="page"><?php the_title(); ?></li>
          </ol>
        </nav>

        <!-- Eyebrow -->
        <p class="sp-eyebrow">Chargeurie &mdash; Chargeur Premium</p>

        <!-- Titre -->
        <h1 class="sp-title"><?php the_title(); ?></h1>

        <!-- Avis (si activés) -->
        <?php if ( wc_reviews_enabled() && $rating_cnt > 0 ) : ?>
          <div class="sp-rating" role="img" aria-label="Note : <?php echo esc_attr(round($avg_rating, 1)); ?> sur 5 (<?php echo esc_attr($rating_cnt); ?> avis)">
            <span class="sp-stars" aria-hidden="true"><?php
              $full = floor($avg_rating);
              $half = ($avg_rating - $full >= 0.5) ? 1 : 0;
              $empty = 5 - $full - $half;
              echo str_repeat('<svg class="star star-full" viewBox="0 0 12 12"><path d="M6 1l1.39 2.82L10.5 4.27l-2.25 2.19.53 3.1L6 8l-2.78 1.56.53-3.1L1.5 4.27l3.11-.45z" fill="currentColor"/></svg>', $full);
              if ($half) echo '<svg class="star star-half" viewBox="0 0 12 12"><path d="M6 1v7L3.22 9.56l.53-3.1L1.5 4.27l3.11-.45z" fill="currentColor"/><path d="M6 1l1.39 2.82L10.5 4.27l-2.25 2.19.53 3.1L6 8z" fill="none" stroke="currentColor" stroke-width=".5"/></svg>';
              echo str_repeat('<svg class="star star-empty" viewBox="0 0 12 12"><path d="M6 1l1.39 2.82L10.5 4.27l-2.25 2.19.53 3.1L6 8l-2.78 1.56.53-3.1L1.5 4.27l3.11-.45z" fill="none" stroke="currentColor" stroke-width=".8"/></svg>', $empty);
            ?></span>
            <span class="sp-rating-count"><?php echo esc_html($rating_cnt); ?> avis</span>
          </div>
        <?php endif; ?>

        <!-- Prix -->
        <div class="sp-price" aria-label="Prix"><?php echo $product->get_price_html(); ?></div>

        <!-- Courte description -->
        <?php if ($short) : ?>
          <div class="sp-short"><?php echo wp_kses_post($short); ?></div>
        <?php endif; ?>

        <!-- Formulaire WooCommerce (variations + quantité + ATC) -->
        <div class="sp-form" id="spForm">
          <?php woocommerce_template_single_add_to_cart(); ?>
        </div>

        <!-- Trust strip -->
        <ul class="sp-trust" aria-label="Garanties">
          <li class="sp-trust-item">
            <span class="sp-trust-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4a1 1 0 011 1v7a1.5 1.5 0 01-3 0 1.5 1.5 0 01-3 0V9a1 1 0 011-1z"/></svg>
            </span>
            <span>Livraison offerte dès <strong>35 €</strong> &middot; <strong>3–4 j</strong> ouvrés</span>
          </li>
          <li class="sp-trust-item">
            <span class="sp-trust-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>
            </span>
            <span>Retours sans frais sous <strong>30 jours</strong></span>
          </li>
          <li class="sp-trust-item">
            <span class="sp-trust-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </span>
            <span>Garantie <strong>2 ans</strong> constructeur</span>
          </li>
          <li class="sp-trust-item sp-trust-payment">
            <span class="sp-trust-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </span>
            <span>Paiement sécurisé &middot; <strong>CB, PayPal, Apple Pay</strong></span>
          </li>
        </ul>

      </div><!-- /sp-col-buy -->
    </div><!-- /sp-hero-inner -->
  </section><!-- /sp-hero -->

  <!-- ── CARACTÉRISTIQUES (fond blanc, tableau propre) ─────────────── -->
  <?php if ( ! empty( $attrs ) ) : ?>
  <section class="sp-specs-section" aria-label="Caractéristiques">
    <div class="chg-container-xl">
      <header class="sp-section-header">
        <p class="sp-tag">Fiche technique</p>
        <h2 class="sp-section-title">Caractéristiques</h2>
      </header>
      <dl class="sp-specs-table">
        <?php foreach ( $attrs as $attribute ) :
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
          <div class="sp-spec-row">
            <dt><?php echo esc_html( $attr_name ); ?></dt>
            <dd><?php echo esc_html( $attr_value ); ?></dd>
          </div>
        <?php endforeach; ?>
      </dl>
    </div>
  </section>
  <?php endif; ?>

  <!-- ── DESCRIPTION + ACCORDIONS (fond off) ──────────────────── -->
  <section class="sp-info-section">
    <div class="sp-info-inner chg-container-xl">

      <?php if ($desc) : ?>
      <div class="sp-desc-col">
        <p class="sp-tag">Description</p>
        <h2 class="sp-section-title sp-section-title--sm">Détails du produit</h2>
        <div class="sp-desc-body"><?php echo wp_kses_post($desc); ?></div>
      </div>
      <?php endif; ?>

      <div class="sp-faq-col">
        <?php if (!$desc) : ?><p class="sp-tag">Informations</p><?php endif; ?>

        <div class="sp-accordions" role="list">

          <div class="sp-accordion" role="listitem">
            <button class="sp-accordion-btn" aria-expanded="false" aria-controls="sp-acc-compat">
              <span>Compatibilité</span>
              <span class="sp-acc-icon" aria-hidden="true"></span>
            </button>
            <div class="sp-accordion-panel" id="sp-acc-compat" hidden>
              <p>Compatible avec tous les appareils USB-C : iPhone 15+, Samsung Galaxy, Google Pixel, MacBook Air/Pro, iPad Pro et tout appareil à port USB-C.</p>
            </div>
          </div>

          <div class="sp-accordion" role="listitem">
            <button class="sp-accordion-btn" aria-expanded="false" aria-controls="sp-acc-livraison">
              <span>Livraison &amp; Retours</span>
              <span class="sp-acc-icon" aria-hidden="true"></span>
            </button>
            <div class="sp-accordion-panel" id="sp-acc-livraison" hidden>
              <p>Expédition sous 24 h les jours ouvrables. Livraison offerte dès 35 €, reçue en 3 à 4 jours ouvrés. Retours acceptés sous 30 jours — produit non utilisé dans son emballage d’origine.</p>
            </div>
          </div>

          <div class="sp-accordion" role="listitem">
            <button class="sp-accordion-btn" aria-expanded="false" aria-controls="sp-acc-garantie">
              <span>Garantie</span>
              <span class="sp-acc-icon" aria-hidden="true"></span>
            </button>
            <div class="sp-accordion-panel" id="sp-acc-garantie" hidden>
              <p>Garantie constructeur 2 ans. En cas de défaut, échange ou remboursement intégral sans condition dans les 30 premiers jours.</p>
            </div>
          </div>

        </div><!-- /sp-accordions -->
      </div><!-- /sp-faq-col -->

    </div><!-- /sp-info-inner -->
  </section>

  <!-- ── PRODUITS ASSOCIÉS ─────────────────────────────────── -->
  <?php
  $related = wc_get_related_products($product->get_id(), 3);
  if (!empty($related)) :
  ?>
  <section class="sp-related-section">
    <div class="chg-container-xl">
      <header class="sp-section-header">
        <p class="products-tag">Vous aimerez aussi</p>
        <h2 class="sp-section-title">Dans la même gamme</h2>
      </header>
      <div class="products-grid">
        <?php foreach ($related as $rid) :
          $rp = wc_get_product($rid);
          if (!$rp || !$rp->is_visible()) continue;
          $rb = function_exists('chg_card_badge') ? chg_card_badge($rp) : null;
        ?>
          <article class="product-card" data-product-id="<?php echo esc_attr($rid); ?>">
            <a href="<?php echo esc_url(get_permalink($rid)); ?>" class="card-img-wrap" tabindex="-1" aria-hidden="true">
              <?php if ($rb) : ?><div class="card-badge badge-<?php echo esc_attr($rb['id']); ?>"><?php echo $rb['label']; ?></div><?php endif; ?>
              <?php $rtid = $rp->get_image_id(); ?>
              <?php if ($rtid) :
                echo wp_get_attachment_image($rtid, 'chg-product-card', false, ['class'=>'slide-product-img','loading'=>'lazy']);
              else : ?>
                <div class="card-placeholder" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
              <?php endif; ?>
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

</main><!-- /chg-sp -->

<!-- ── STICKY ATC BAR (mobile + desktop scroll) ─────────────── -->
<div class="sp-sticky-bar" id="spStickyBar" aria-hidden="true">
  <div class="sp-sticky-inner">
    <div class="sp-sticky-info">
      <?php if ($image_id) : ?>
        <?php echo wp_get_attachment_image($image_id, [48,48], false, ['class'=>'sp-sticky-thumb','loading'=>'lazy']); ?>
      <?php endif; ?>
      <span class="sp-sticky-name"><?php the_title(); ?></span>
    </div>
    <div class="sp-sticky-price"><?php echo $product->get_price_html(); ?></div>
    <button class="sp-sticky-btn" id="spStickyBtn" type="button">
      Ajouter au panier
    </button>
  </div>
</div>

<script>
/* === Fiche produit — JS autonome === */
(function(){
  'use strict';

  /* ── 1. Galerie : swap image principale ──────────────────── */
  var mainImg = document.getElementById('spMainImg');
  var thumbs  = document.querySelectorAll('.sp-thumb');

  function swapImg(src){
    if (!mainImg) return;
    mainImg.style.opacity = '0';
    mainImg.src = src;
    mainImg.onload = function(){ mainImg.style.opacity = '1'; };
  }

  thumbs.forEach(function(btn){
    btn.addEventListener('click', function(){
      thumbs.forEach(function(b){
        b.classList.remove('is-active');
        b.setAttribute('aria-selected','false');
      });
      btn.classList.add('is-active');
      btn.setAttribute('aria-selected','true');
      swapImg(btn.dataset.full);
    });
  });

  /* ── 2. Variation WooCommerce ────────────────────────── */
  document.addEventListener('DOMContentLoaded', function(){
    var form = document.querySelector('form.variations_form');
    if (!form || typeof jQuery === 'undefined') return;

    jQuery(form)
      .on('found_variation', function(e, v){
        if (v.image && v.image.full_src){
          swapImg(v.image.full_src);
          thumbs.forEach(function(b){
            b.classList.remove('is-active');
            b.setAttribute('aria-selected','false');
          });
          var match = Array.from(thumbs).find(function(b){
            return b.dataset.full === v.image.full_src;
          });
          if (match){
            match.classList.add('is-active');
            match.setAttribute('aria-selected','true');
          }
        }
      })
      .on('reset_data', function(){
        var first = thumbs[0];
        if (first){
          thumbs.forEach(function(b){
            b.classList.remove('is-active');
            b.setAttribute('aria-selected','false');
          });
          first.classList.add('is-active');
          first.setAttribute('aria-selected','true');
          swapImg(first.dataset.full);
        }
      });
  });

  /* ── 3. Accordions accessibles ──────────────────────── */
  document.querySelectorAll('.sp-accordion-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
      var expanded = btn.getAttribute('aria-expanded') === 'true';
      var panelId  = btn.getAttribute('aria-controls');
      var panel    = document.getElementById(panelId);
      if (!panel) return;

      if (expanded){
        btn.setAttribute('aria-expanded','false');
        /* Animer la fermeture */
        panel.style.height = panel.scrollHeight + 'px';
        panel.style.overflow = 'hidden';
        requestAnimationFrame(function(){
          panel.style.transition = 'height .35s cubic-bezier(.4,0,.2,1), opacity .3s';
          panel.style.height  = '0';
          panel.style.opacity = '0';
        });
        panel.addEventListener('transitionend', function h(){
          panel.hidden = true;
          panel.style = '';
          panel.removeEventListener('transitionend', h);
        });
      } else {
        /* Ouvrir les autres d’abord (comportement un seul ouvert à la fois) */
        document.querySelectorAll('.sp-accordion-btn[aria-expanded="true"]').forEach(function(ob){
          if (ob !== btn) ob.click();
        });
        btn.setAttribute('aria-expanded','true');
        panel.hidden = false;
        var h = panel.scrollHeight;
        panel.style.height  = '0';
        panel.style.opacity = '0';
        panel.style.overflow = 'hidden';
        requestAnimationFrame(function(){
          panel.style.transition = 'height .35s cubic-bezier(.4,0,.2,1), opacity .3s';
          panel.style.height  = h + 'px';
          panel.style.opacity = '1';
        });
        panel.addEventListener('transitionend', function h2(){
          panel.style.height   = '';
          panel.style.overflow = '';
          panel.removeEventListener('transitionend', h2);
        });
      }
    });
  });

  /* ── 4. Sticky ATC bar ─────────────────────────────── */
  var stickyBar = document.getElementById('spStickyBar');
  var spForm    = document.getElementById('spForm');
  var stickyBtn = document.getElementById('spStickyBtn');

  if (stickyBar && spForm){
    var observer = new IntersectionObserver(function(entries){
      var outOfView = !entries[0].isIntersecting;
      stickyBar.classList.toggle('is-visible', outOfView);
      stickyBar.setAttribute('aria-hidden', String(!outOfView));
    }, { threshold: 0.1 });
    observer.observe(spForm);

    if (stickyBtn){
      stickyBtn.addEventListener('click', function(){
        var realBtn = spForm.querySelector('.single_add_to_cart_button, button[type="submit"]');
        if (realBtn && !realBtn.disabled) realBtn.click();
        else spForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
      });
    }
  }

  /* ── 5. Nav color fix : la sp-hero n’a pas la classe .hero ───── */
  /* Le JS du thème cherche .hero — on expose la section au ScrollTrigger */
  var spHero = document.querySelector('.sp-hero');
  if (spHero) spHero.classList.add('hero');

})();
</script>

<?php endwhile; get_footer(); ?>
