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
$manifesto_sub = esc_html( chg_option( 'chg_manifesto_sub', "La technologie la plus utile est celle que vous avez toujours sur vous. Chargeurie conçoit des accessoires qui disparaissent dans votre quotidien — jusqu'au moment où vous en avez besoin." ) );
$ticker_text   = esc_html( chg_option( 'chg_ticker_text', 'Charge Rapide 60 W · USB-C 3A · 5 Coloris · Lanière Téléphone · Câble Bracelet · 4-en-1 · Livraison offerte dès 35 € · Garantie 2 ans ·' ) );

$hero_img_desktop_id = intval( get_theme_mod( 'chg_hero_img_desktop', 0 ) );
$hero_img_mobile_id  = intval( get_theme_mod( 'chg_hero_img_mobile',  0 ) );
$hero_overlay        = esc_attr( get_theme_mod( 'chg_hero_overlay', '0.55' ) );

$stats = array();
for ( $i = 1; $i <= 4; $i++ ) {
    $defaults_num    = array( '60', '5', '4', '2' );
    $defaults_unit   = array( 'Puissance', 'Coloris', 'En 1', 'Garantie' );
    $defaults_suffix = array( 'W', '', '', 'ans' );
    $stats[] = array(
        'num'    => chg_option( "chg_stat_{$i}_num",    $defaults_num[$i-1] ),
        'unit'   => chg_option( "chg_stat_{$i}_unit",   $defaults_unit[$i-1] ),
        'suffix' => chg_option( "chg_stat_{$i}_suffix", $defaults_suffix[$i-1] ),
    );
}

// ---- Section Gamme : options Customizer ----
$gamme_tag     = esc_html( get_theme_mod( 'chg_gamme_tag',     'Gamme' ) );
$gamme_title   = esc_html( get_theme_mod( 'chg_gamme_title',   'Nos Lanieres' ) );
$gamme_count   = max( 1, min( 6, intval( get_theme_mod( 'chg_gamme_count', 3 ) ) ) );
$gamme_cat     = sanitize_text_field( get_theme_mod( 'chg_gamme_category', '' ) );
$gamme_viewall = esc_html( get_theme_mod( 'chg_gamme_viewall', 'Voir tout' ) );

$shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#';

// Requête produits : par catégorie si slug défini, sinon les derniers
$products = array();
if ( class_exists( 'WooCommerce' ) ) {
    $query_args = array(
        'limit'   => $gamme_count,
        'orderby' => 'date',
        'order'   => 'DESC',
        'status'  => 'publish',
    );
    if ( ! empty( $gamme_cat ) ) {
        $query_args['category'] = array( $gamme_cat );
    }
    $q        = new WC_Product_Query( $query_args );
    $products = $q->get_products();
}

// ---- Section Reveal : options Customizer ----
$reveal_title1   = esc_html( get_theme_mod( 'chg_reveal_title1',   'La Lanière' ) );
$reveal_title2   = esc_html( get_theme_mod( 'chg_reveal_title2',   'USB-C 3A' ) );
$reveal_desc     = esc_html( get_theme_mod( 'chg_reveal_desc',     "Notre accessoire le plus emblématique. Câble USB-C 3A intégré dans une lanière portée au cou. La charge toujours à portée de main, le téléphone jamais perdu." ) );
$reveal_cta_text = esc_html( get_theme_mod( 'chg_reveal_cta_text', 'Découvrir' ) );
$reveal_cta_raw  = get_theme_mod( 'chg_reveal_cta_url', '' );
$reveal_cta_url  = ! empty( $reveal_cta_raw ) ? esc_url( $reveal_cta_raw ) : esc_url( $shop_url );

$reveal_specs = array();
$spec_defaults = array(
    1 => array( 'label' => 'Puissance',      'value' => '3A — Charge rapide 18 W' ),
    2 => array( 'label' => 'Longueur câble', 'value' => '13,5 cm' ),
    3 => array( 'label' => 'Coloris',        'value' => '5 teintes disponibles' ),
    4 => array( 'label' => 'Compatibilité',  'value' => 'iPhone & Android USB-C' ),
    5 => array( 'label' => 'Livraison',      'value' => 'Offerte dès 35 €' ),
);
for ( $i = 1; $i <= 5; $i++ ) {
    $reveal_specs[] = array(
        'label' => esc_html( get_theme_mod( "chg_reveal_spec_{$i}_label", $spec_defaults[$i]['label'] ) ),
        'value' => esc_html( get_theme_mod( "chg_reveal_spec_{$i}_value", $spec_defaults[$i]['value'] ) ),
    );
}

// ---- Section Blog : 3 derniers articles ----
$blog_tag      = esc_html( get_theme_mod( 'chg_blog_tag',   'Blog' ) );
$blog_title    = esc_html( get_theme_mod( 'chg_blog_title', 'Actualités' ) );
$blog_viewall  = esc_html( get_theme_mod( 'chg_blog_viewall', 'Tous les articles' ) );
$blog_url      = get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' );
$blog_posts    = new WP_Query( [
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
] );

$manifesto_words = explode( ' ', esc_html( $manifesto_raw ) );
?>

<!-- TICKER -->
<div class="ticker-wrap" id="tickerWrap">
  <div class="ticker-track">
    <?php for ( $t = 0; $t < 4; $t++ ) { ?>
      <span class="ticker-item"><?php echo $ticker_text; ?></span>
    <?php } ?>
  </div>
</div>

<!-- HERO -->
<section class="hero" id="hero">
  <canvas id="heroCanvas" aria-hidden="true"></canvas>

  <?php if ( $hero_img_desktop_id || $hero_img_mobile_id ) { ?>
  <div class="hero-bg" aria-hidden="true">
    <?php if ( $hero_img_desktop_id ) { ?>
      <img src="<?php echo esc_url( wp_get_attachment_image_url( $hero_img_desktop_id, 'chg-hero' ) ); ?>" alt="" class="hero-bg-desktop" loading="eager" fetchpriority="high">
    <?php } ?>
    <?php if ( $hero_img_mobile_id ) { ?>
      <img src="<?php echo esc_url( wp_get_attachment_image_url( $hero_img_mobile_id, 'chg-product-featured' ) ); ?>" alt="" class="hero-bg-mobile" loading="eager">
    <?php } elseif ( $hero_img_desktop_id ) { ?>
      <img src="<?php echo esc_url( wp_get_attachment_image_url( $hero_img_desktop_id, 'chg-hero' ) ); ?>" alt="" class="hero-bg-mobile" loading="eager">
    <?php } ?>
    <div class="hero-overlay" style="--overlay-opacity:<?php echo $hero_overlay; ?>"></div>
  </div>
  <?php } ?>

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
  <?php foreach ( $stats as $s ) { ?>
  <div class="stat-item">
    <div class="stat-num count-up" data-target="<?php echo esc_attr( $s['num'] ); ?>">
      <?php echo esc_html( $s['num'] ); ?><span class="stat-suffix"><?php echo esc_html( $s['suffix'] ); ?></span>
    </div>
    <div class="stat-unit"><?php echo esc_html( $s['unit'] ); ?></div>
  </div>
  <?php } ?>
</section>

<!-- MANIFESTE -->
<section class="manifesto" id="manifesto">
  <div class="manifesto-label"><?php echo $manifesto_lbl; ?></div>
  <div class="manifesto-text" id="manifestoText">
    <?php foreach ( $manifesto_words as $word ) { ?>
      <span class="manifesto-word"><?php echo esc_html( $word ); ?></span>
    <?php } ?>
  </div>
  <p class="manifesto-sub"><?php echo $manifesto_sub; ?></p>
</section>

<!-- PRODUITS (GAMME) -->
<section class="products-section" id="products">
  <header class="products-header">
    <div class="products-tag"><?php echo $gamme_tag; ?></div>
    <h2 class="products-title"><?php echo $gamme_title; ?></h2>
    <a href="<?php echo esc_url( $shop_url ); ?>" class="products-viewall"><?php echo $gamme_viewall; ?> &rarr;</a>
  </header>
  <div class="products-grid" id="productsGrid">
    <?php if ( ! empty( $products ) ) { ?>
      <?php foreach ( $products as $product ) { ?>
        <?php $badge = chg_card_badge( $product ); ?>
        <div class="product-card" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
          <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="card-img-wrap">
            <?php if ( $badge ) { ?>
              <div class="card-badge badge-<?php echo esc_attr( $badge['id'] ); ?>"><?php echo $badge['label']; ?></div>
            <?php } ?>
            <?php $tid = $product->get_image_id(); ?>
            <?php if ( $tid ) {
              echo wp_get_attachment_image( $tid, 'chg-product-card', false, array('class'=>'slide-product-img','loading'=>'lazy') );
            } else { ?>
              <div class="card-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M8 1.5L2.5 8H7L5.5 12.5L12 6H7.5L8 1.5Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round" opacity=".15"/></svg>
              </div>
            <?php } ?>
          </a>
          <div class="card-info">
            <div class="card-name"><?php echo esc_html( $product->get_name() ); ?></div>
            <div class="card-bottom">
              <div class="card-price"><?php echo $product->get_price_html(); ?></div>
              <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="card-add">Voir</a>
            </div>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <div class="no-products-notice"><p>Aucun produit. <a href="<?php echo esc_url( admin_url('post-new.php?post_type=product') ); ?>">Ajouter &rarr;</a></p></div>
    <?php } ?>
  </div>
</section>

<!-- REVEAL (PRODUIT VEDETTE) -->
<section id="reveal" class="reveal-section">
  <div class="reveal-left">
    <h2><?php echo $reveal_title1; ?><br><em><?php echo $reveal_title2; ?></em></h2>
    <p><?php echo $reveal_desc; ?></p>
    <div class="cta-row"><a href="<?php echo $reveal_cta_url; ?>" class="btn-dark"><?php echo $reveal_cta_text; ?> &rarr;</a></div>
  </div>
  <div class="reveal-right">
    <?php foreach ( $reveal_specs as $spec ) { ?>
      <div class="spec-row">
        <div class="spec-label"><?php echo $spec['label']; ?></div>
        <div class="spec-value"><?php echo $spec['value']; ?></div>
      </div>
    <?php } ?>
  </div>
</section>

<!-- BLOG -->
<?php if ( $blog_posts->have_posts() ) : ?>
<section class="chg-home-blog" id="blog">
  <div class="chg-home-blog-inner">

    <header class="chg-home-blog-header">
      <div class="chg-home-blog-tag"><?php echo $blog_tag; ?></div>
      <h2 class="chg-home-blog-title"><?php echo $blog_title; ?></h2>
      <a href="<?php echo esc_url( $blog_url ); ?>" class="chg-home-blog-viewall">
        <?php echo $blog_viewall; ?> &rarr;
      </a>
    </header>

    <div class="chg-home-blog-grid">
      <?php
      $first = true;
      while ( $blog_posts->have_posts() ) :
        $blog_posts->the_post();
        $cats = get_the_category();
        $cat  = $cats ? $cats[0] : null;
        $read_time = max( 1, round( str_word_count( strip_tags( get_the_content() ) ) / 200 ) );
      ?>
      <article class="chg-home-post-card<?php echo $first ? ' chg-home-post-card--featured' : ''; ?>">
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>" class="chg-home-post-img" aria-hidden="true" tabindex="-1">
            <?php the_post_thumbnail( $first ? 'large' : 'medium_large', [ 'loading' => 'lazy' ] ); ?>
          </a>
        <?php else : ?>
          <a href="<?php the_permalink(); ?>" class="chg-home-post-img chg-home-post-img--empty" aria-hidden="true" tabindex="-1">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          </a>
        <?php endif; ?>

        <div class="chg-home-post-body">
          <div class="chg-post-meta">
            <?php if ( $cat ) : ?>
              <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="chg-post-cat">
                <?php echo esc_html( $cat->name ); ?>
              </a>
            <?php endif; ?>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="chg-post-date">
              <?php echo esc_html( get_the_date() ); ?>
            </time>
            <span class="chg-post-readtime"><?php printf( esc_html__( '%d min', 'chargeurie' ), $read_time ); ?></span>
          </div>

          <h3 class="chg-home-post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>

          <?php if ( $first ) : ?>
            <p class="chg-home-post-excerpt">
              <?php echo wp_trim_words( get_the_excerpt(), 22, '…' ); ?>
            </p>
          <?php endif; ?>

          <a href="<?php the_permalink(); ?>" class="chg-post-card-link" aria-label="<?php echo esc_attr( sprintf( __( 'Lire %s', 'chargeurie' ), get_the_title() ) ); ?>">
            <?php esc_html_e( 'Lire', 'chargeurie' ); ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </article>
      <?php $first = false; endwhile; wp_reset_postdata(); ?>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- NEWSLETTER -->
<section class="newsletter-section" id="nl">
  <div class="nl-inner">
    <div class="nl-tag">Rester informé</div>
    <h2 class="nl-title">-15% sur votre<br>première commande</h2>
    <p class="nl-sub">Recevez les nouveautés et offres exclusives directement dans votre boîte mail.</p>
    <?php if ( shortcode_exists( 'mc4wp_form' ) ) {
        $mc_id = chg_option( 'chg_mc4wp_form_id', '' );
        echo $mc_id ? do_shortcode( '[mc4wp_form id="' . intval($mc_id) . '"]' ) : do_shortcode('[mc4wp_form]');
    } else { ?>
      <form class="nl-form" onsubmit="event.preventDefault();this.innerHTML='<span style=\'color:var(--blue)\'>Merci. Bienvenue !</span>';">
        <input type="email" placeholder="votre@email.com" required class="nl-input">
        <button type="submit" class="btn-dark nl-btn">S'inscrire</button>
      </form>
    <?php } ?>
  </div>
</section>

<?php get_footer(); ?>
