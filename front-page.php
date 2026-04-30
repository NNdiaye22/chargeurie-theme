<?php
/**
 * Chargeurie — front-page.php
 */
get_header();

$hero_l1       = esc_html( chg_option( 'chg_hero_line1',    'PORTEZ' ) );
$hero_l2       = esc_html( chg_option( 'chg_hero_line2',    'VOTRE' ) );
$hero_l3       = esc_html( chg_option( 'chg_hero_line3',    'CHARGE' ) );
$hero_eyebrow  = esc_html( chg_option( 'chg_hero_eyebrow',  'Câbles & Accessoires de Charge — France' ) );
$hero_sub      = esc_html( chg_option( 'chg_hero_subtitle', 'Des câbles pensés pour être portés au quotidien — autour du cou, au poignet, en porte-clé.' ) );
$hero_meta     = esc_html( chg_option( 'chg_hero_meta',     'USB-C · 60 W · 5 coloris' ) );
$hero_cta      = esc_html( chg_option( 'chg_hero_cta',      'Découvrir la gamme' ) );
$manifesto_lbl = esc_html( chg_option( 'chg_manifesto_label', 'Notre conviction' ) );
$manifesto_raw = chg_option( 'chg_manifesto_text', 'Conçu pour être porté, pas rangé.' );
$manifesto_sub = esc_html( chg_option( 'chg_manifesto_sub', 'La technologie la plus utile est celle que vous avez toujours sur vous. Chargeurie conçoit des accessoires qui disparaissent dans votre quotidien — jusqu\'au moment où vous en avez besoin.' ) );
$ticker_text   = esc_html( chg_option( 'chg_ticker_text', 'Charge Rapide 60 W · USB-C 3A · 5 Coloris · Lanière Téléphone · Câble Bracelet · 4-en-1 · Livraison offerte dès 35 € · Garantie 2 ans ·' ) );

$hero_img_desktop_id = intval( get_theme_mod( 'chg_hero_img_desktop', 0 ) );
$hero_img_mobile_id  = intval( get_theme_mod( 'chg_hero_img_mobile',  0 ) );
$hero_overlay        = esc_attr( get_theme_mod( 'chg_hero_overlay', '0.55' ) );

$stats = [];
for ( $i = 1; $i <= 4; $i++ ) {
    $stats[] = [
        'num'    => chg_option( "chg_stat_{$i}_num",    ['60','5','4','2'][$i-1] ),
        'unit'   => chg_option( "chg_stat_{$i}_unit",   ['Puissance','Coloris','En 1','Garantie'][$i-1] ),
        'suffix' => chg_option( "chg_stat_{$i}_suffix", ['W','','','ans'][$i-1] ),
    ];
}

$products = [];
if ( class_exists( 'WooCommerce' ) ) {
    $q = new WC_Product_Query(['limit'=>3,'orderby'=>'date','order'=>'DESC','status'=>'publish']);
    $products = $q->get_products();
}
$shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#';

$manifesto_words = explode( ' ', esc_html( $manifesto_raw ) );
?>

<!-- TICKER -->
<div class="ticker-wrap" id="tickerWrap">
  <div class="ticker-track">
    <?php for ( $t = 0; $t < 4; $t++ ) : ?>
      <span class="ticker-item"><?php echo $ticker_text; ?></span>
    <?php endfor; ?>
  </div>
</div>

<!-- HERO -->
<section class="hero" id="hero">
  <canvas id="heroCanvas" aria-hidden="true"></canvas>

  <?php if ( $hero_img_desktop_id || $hero_img_mobile_id ) : ?>
  <div class="hero-bg" aria-hidden="true">
    <?php if ( $hero_img_desktop_id ) : ?>
      <img src="<?php echo esc_url( wp_get_attachment_image_url( $hero_img_desktop_id, 'chg-hero' ) ); ?>" alt="" class="hero-bg-desktop" loading="eager" fetchpriority="high">
    <?php endif; ?>
    <?php if ( $hero_img_mobile_id ) : ?>
      <img src="<?php echo esc_url( wp_get_attachment_image_url( $hero_img_mobile_id, 'chg-product-featured' ) ); ?>" alt="" class="hero-bg-mobile" loading="eager">
    <?php elseif ( $hero_img_desktop_id ) : ?>
      <img src="<?php echo esc_url( wp_get_attachment_image_url( $hero_img_desktop_id, 'chg-hero' ) ); ?>" alt="" class="hero-bg-mobile" loading="eager">
    <?php endif; ?>
    <div class="hero-overlay" style="--overlay-opacity:<?php echo $hero_overlay; ?>"></div>
  </div>
  <?php endif; ?>

  <div class="hero-eyebrow"><?php echo $hero_eyebrow; ?></div>

  <h1 class="hero-title">
    <span class="clip"><span class="hero-line"><?php echo $hero_l1; ?></span></span>
    <span class="clip"><span class="hero-line hero-outline"><?php echo $hero_l2; ?></span></span>
    <span class="clip"><span class="hero-line"><?php echo $hero_l3; ?><span class="hero-dot">.</span></span></span>
    <span class="hero-underline"></span>
  </h1>

  <div class="hero-bottom">
    <div>
      <p class="hero-sub"><?php echo $hero_sub; ?></p>
      <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-dark hero-cta"><?php echo $hero_cta; ?> <span class="hero-arrow">&rarr;</span></a>
    </div>
    <div class="hero-meta"><?php echo $hero_meta; ?></div>
  </div>

  <div class="scroll-hint" id="scrollHint">
    <div class="sh-inner"><div class="sh-line"></div></div>
  </div>
</section>

<!-- STATS -->
<section class="stats" id="stats">
  <?php foreach ( $stats as $s ) : ?>
  <div class="stat-item">
    <div class="stat-num count-up" data-target="<?php echo esc_attr( $s['num'] ); ?>">
      <?php echo esc_html( $s['num'] ); ?><span class="stat-suffix"><?php echo esc_html( $s['suffix'] ); ?></span>
    </div>
    <div class="stat-unit"><?php echo esc_html( $s['unit'] ); ?></div>
  </div>
  <?php endforeach; ?>
</section>

<!-- MANIFESTE -->
<section class="manifesto" id="manifesto">
  <div class="manifesto-label"><?php echo $manifesto_lbl; ?></div>
  <div class="manifesto-text" id="manifestoText">
    <?php foreach ( $manifesto_words as $word ) : ?>
      <span class="manifesto-word"><?php echo esc_html( $word ); ?></span>
    <?php endforeach; ?>
  </div>
  <p class="manifesto-sub"><?php echo $manifesto_sub; ?></p>
</section>

<!-- PRODUITS -->
<section class="products-section" id="products">
  <header class="products-header">
    <div class="products-tag">Gamme</div>
    <h2 class="products-title">Nos accessoires</h2>
    <a href="<?php echo esc_url( $shop_url ); ?>" class="products-viewall">Voir tout &rarr;</a>
  </header>
  <div class="products-grid" id="productsGrid">
    <?php if ( ! empty( $products ) ) : ?>
      <?php foreach ( $products as $product ) : ?>
        <?php $badge = chg_card_badge( $product ); ?>
        <div class="product-card" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
          <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="card-img-wrap">
            <?php if ( $badge ) : ?>
              <div class="card-badge badge-<?php echo esc_attr( $badge['id'] ); ?>"><?php echo $badge['label']; ?></div>
            <?php endif; ?>
            <?php $tid = $product->get_image_id(); ?>
            <?php if ( $tid ) : echo wp_get_attachment_image( $tid, 'chg-product-card', false, ['class'=>'slide-product-img','loading'=>'lazy'] );
            else : ?>
              <div class="card-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M8 1.5L2.5 8H7L5.5 12.5L12 6H7.5L8 1.5Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round" opacity=".15"/></svg>
              </div>
            <?php endif; ?>
          </a>
          <div class="card-info">
            <div class="card-name"><?php echo esc_html( $product->get_name() ); ?></div>
            <div class="card-bottom">
              <div class="card-price"><?php echo $product->get_price_html(); ?></div>
              <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="card-add">Voir</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <div class="no-products-notice"><p>Aucun produit. <a href="<?php echo esc_url( admin_url('post-new.php?post_type=product') ); ?>">Ajouter &rarr;</a></p></div>
    <?php endif; ?>
  </div>
</section>

<!-- REVEAL -->
<section id="reveal" class="reveal-section">
  <div class="reveal-left">
    <h2>La Lanière<br><em>USB-C 3A</em></h2>
    <p>Notre accessoire le plus emblématique. Câble USB-C 3A intégré dans une lanière portée au cou. La charge toujours à portée de main, le téléphone jamais perdu.</p>
    <div class="cta-row"><a href="<?php echo esc_url( $shop_url ); ?>" class="btn-dark">Découvrir &rarr;</a></div>
  </div>
  <div class="reveal-right">
    <div class="spec-row"><div class="spec-label">Puissance</div><div class="spec-value">3A — Charge rapide 18 W</div></div>
    <div class="spec-row"><div class="spec-label">Longueur câble</div><div class="spec-value">13,5 cm</div></div>
    <div class="spec-row"><div class="spec-label">Coloris</div><div class="spec-value">5 teintes disponibles</div></div>
    <div class="spec-row"><div class="spec-label">Compatibilité</div><div class="spec-value">iPhone &amp; Android USB-C</div></div>
    <div class="spec-row"><div class="spec-label">Livraison</div><div class="spec-value">Offerte dès 35 €</div></div>
  </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter-section" id="nl">
  <div class="nl-inner">
    <div class="nl-tag">Rester informé</div>
    <h2 class="nl-title">-15% sur votre<br>première commande</h2>
    <p class="nl-sub">Recevez les nouveautés et offres exclusives directement dans votre boîte mail.</p>
    <?php if ( shortcode_exists( 'mc4wp_form' ) ) :
        $mc_id = chg_option( 'chg_mc4wp_form_id', '' );
        echo $mc_id ? do_shortcode( '[mc4wp_form id="' . intval($mc_id) . '"]' ) : do_shortcode('[mc4wp_form]');
    else : ?>
      <form class="nl-form" onsubmit="event.preventDefault();this.innerHTML='<span style=\'color:var(--blue)\'>Merci. Bienvenue !</span>';">
        <input type="email" placeholder="votre@email.com" required class="nl-input">
        <button type="submit" class="btn-dark nl-btn">S'inscrire</button>
      </form>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
