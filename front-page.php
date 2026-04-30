<?php
/**
 * Chargeurie — front-page.php
 * Homepage : ticker · hero · stats · manifeste · produits · CTA final
 */
get_header();

$hero_l1  = esc_html( chg_option( 'chg_hero_line1',    'PORTEZ' ) );
$hero_l2  = esc_html( chg_option( 'chg_hero_line2',    'VOTRE' ) );
$hero_l3  = esc_html( chg_option( 'chg_hero_line3',    'CHARGE' ) );
$hero_sub = esc_html( chg_option( 'chg_hero_subtitle', 'Des câbles pensés pour être portés au quotidien — autour du cou, au poignet, en porte-clé.' ) );
$hero_cta = esc_html( chg_option( 'chg_hero_cta',      'Découvrir la gamme' ) );
$manifesto     = esc_html( chg_option( 'chg_manifesto_text', 'Conçu pour être porté, pas rangé.' ) );
$manifesto_sub = esc_html( chg_option( 'chg_manifesto_sub', 'La technologie la plus utile est celle que vous avez toujours sur vous.' ) );
$ticker_text   = esc_html( chg_option( 'chg_ticker_text', 'Charge Rapide 60 W · USB-C 3A · 5 Coloris · Lanière Téléphone · Câble Bracelet · 4-en-1 · Livraison offerte dès 35 € · Garantie 2 ans ·' ) );

$stats = [];
for ( $i = 1; $i <= 4; $i++ ) {
    $stats[] = [
        'num'    => chg_option( "chg_stat_{$i}_num",    ['60','5','4','2'][$i-1] ),
        'unit'   => chg_option( "chg_stat_{$i}_unit",   ['Puissance','Coloris','En 1','Garantie'][$i-1] ),
        'suffix' => chg_option( "chg_stat_{$i}_suffix", ['W','','','ans'][$i-1] ),
        'desc'   => chg_option( "chg_stat_{$i}_desc",   '' ),
    ];
}

$products = [];
if ( class_exists( 'WooCommerce' ) ) {
    $query = new WC_Product_Query([
        'limit'   => 3,
        'orderby' => 'date',
        'order'   => 'DESC',
        'status'  => 'publish',
    ]);
    $products = $query->get_products();
}
$shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#';
?>

<div class="ticker-wrap" id="tickerWrap">
  <div class="ticker-track">
    <?php for ( $t = 0; $t < 4; $t++ ) : ?>
      <span class="ticker-item"><?php echo $ticker_text; ?></span>
    <?php endfor; ?>
  </div>
</div>

<section class="hero" id="hero">
  <canvas id="heroCanvas" aria-hidden="true"></canvas>
  <div class="hero-text">
    <h1 class="hero-title">
      <span class="hero-line solid"><?php echo $hero_l1; ?></span>
      <span class="hero-line outline"><?php echo $hero_l2; ?></span>
      <span class="hero-line solid"><?php echo $hero_l3; ?></span>
    </h1>
    <p class="hero-sub"><?php echo $hero_sub; ?></p>
    <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-dark hero-cta"><?php echo $hero_cta; ?></a>
  </div>
  <div class="scroll-hint" id="scrollHint">
    <div class="sh-inner">
      <span class="sh-text">SCROLL</span>
      <div class="sh-line"></div>
    </div>
  </div>
</section>

<section class="stats" id="stats">
  <?php foreach ( $stats as $s ) : ?>
  <div class="stat-item">
    <div class="stat-num"><?php echo esc_html( $s['num'] ); ?><span class="stat-suffix"><?php echo esc_html( $s['suffix'] ); ?></span></div>
    <div class="stat-unit"><?php echo esc_html( $s['unit'] ); ?></div>
    <div class="stat-desc"><?php echo esc_html( $s['desc'] ); ?></div>
  </div>
  <?php endforeach; ?>
</section>

<section class="manifesto" id="manifesto">
  <div class="manifesto-inner">
    <p class="manifesto-text" id="manifestoText"><?php echo $manifesto; ?></p>
    <p class="manifesto-sub"><?php echo $manifesto_sub; ?></p>
  </div>
</section>

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
            <?php $thumb_id = $product->get_image_id(); ?>
            <?php if ( $thumb_id ) : ?>
              <?php echo wp_get_attachment_image( $thumb_id, 'chg-product-card', false, ['class' => 'slide-product-img', 'loading' => 'lazy'] ); ?>
            <?php else : ?>
              <div class="card-placeholder"><span class="card-placeholder-icon">&#9889;</span></div>
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
      <div class="no-products-notice">
        <p>Aucun produit pour l'instant. <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=product' ) ); ?>">Ajouter un produit &rarr;</a></p>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="reveal-section" id="reveal">
  <div class="reveal-inner">
    <div class="reveal-tag">Notre emblème</div>
    <h2 class="reveal-title">La lanière<br><span class="outline-text">USB-C 3A</span></h2>
    <p class="reveal-sub">Notre accessoire le plus emblématique. Câble USB-C 3A intégré dans une lanière portée au cou. La charge toujours à portée de main, le téléphone jamais perdu.</p>
    <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-dark">Découvrir &rarr;</a>
  </div>
</section>

<section class="newsletter-section" id="nl">
  <div class="nl-inner">
    <div class="nl-tag">Rester informé</div>
    <h2 class="nl-title">-15% sur votre<br>première commande</h2>
    <p class="nl-sub">Recevez les nouveautés et offres exclusives directement dans votre boîte mail.</p>
    <?php
    if ( shortcode_exists( 'mc4wp_form' ) ) {
        $mc_id = chg_option( 'chg_mc4wp_form_id', '' );
        echo $mc_id ? do_shortcode( '[mc4wp_form id="' . intval( $mc_id ) . '"]' ) : do_shortcode( '[mc4wp_form]' );
    } else { ?>
      <form class="nl-form" onsubmit="event.preventDefault();this.innerHTML='<span style=\'color:var(--blue)\'>Merci ! &#127881;</span>';">
        <input type="email" placeholder="votre@email.com" required class="nl-input">
        <button type="submit" class="btn-dark nl-btn">S'inscrire</button>
      </form>
    <?php } ?>
  </div>
</section>

<?php get_footer(); ?>
