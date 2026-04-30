<?php
/**
 * Chargeurie — front-page.php
 * Homepage convertie depuis chargeurie-v3-final.html
 */
get_header();

$hero_l1  = chg_option( 'chg_hero_line1',    'PORTEZ' );
$hero_l2  = chg_option( 'chg_hero_line2',    'VOTRE' );
$hero_l3  = chg_option( 'chg_hero_line3',    'CHARGE' );
$hero_sub = chg_option( 'chg_hero_subtitle', "Des c\u00e2bles pens\u00e9s pour \u00eatre port\u00e9s au quotidien \u2014 autour du cou, au poignet, en porte-cl\u00e9." );
$hero_cta = chg_option( 'chg_hero_cta',      'D\u00e9couvrir la gamme' );
$mani_txt = chg_option( 'chg_manifesto_text','Con\u00e7u pour \u00eatre port\u00e9, pas rang\u00e9.' );
$mani_sub = chg_option( 'chg_manifesto_sub', "La technologie la plus utile est celle que vous avez toujours sur vous. Chargeurie con\u00e7oit des accessoires qui disparaissent dans votre quotidien \u2014 jusqu'au moment o\u00f9 vous en avez besoin." );
$ticker   = chg_option( 'chg_ticker_text',   'Charge Rapide 60 W \u00b7 USB-C 3A \u00b7 5 Coloris \u00b7 Lani\u00e8re T\u00e9l\u00e9phone \u00b7 C\u00e2ble Bracelet \u00b7 4-en-1 \u00b7 Livraison offerte d\u00e8s 35 \u20ac \u00b7 Garantie 2 ans \u00b7' );
$shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#';
?>

<div class="ticker-wrap"><div class="ticker" id="ticker">
  <?php for ( $i = 0; $i < 6; $i++ ) : ?>
    <span class="ticker-item"><?php echo esc_html( $ticker ); ?></span>
  <?php endfor; ?>
</div></div>

<section class="hero" id="hero">
  <div class="hero-inner">
    <div class="hero-headline" id="heroHeadline">
      <span class="h-solid"><?php echo esc_html( $hero_l1 ); ?></span><br>
      <span class="h-outline"><?php echo esc_html( $hero_l2 ); ?></span><br>
      <span class="h-solid"><?php echo esc_html( $hero_l3 ); ?></span>
    </div>
    <p class="hero-sub" id="heroSub"><?php echo esc_html( $hero_sub ); ?></p>
    <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-dark" id="heroCta"><?php echo esc_html( $hero_cta ); ?></a>
  </div>
  <div class="hero-scroll-hint" id="heroScroll">
    <span>Scroll</span>
    <span class="scroll-line"></span>
  </div>
</section>

<section class="reveal-section" id="reveal">
  <div class="reveal-sticky">
    <div class="rs-left">
      <div class="rs-tag reveal-elem">La Lani\u00e8re USB-C</div>
      <h2 class="rs-title reveal-elem">Un c\u00e2ble.<br>Un accessoire.</h2>
      <p class="rs-sub reveal-elem">La Lani\u00e8re T\u00e9l\u00e9phone USB-C combine charge rapide 3A et port quotidien. Tress\u00e9e pour durer, con\u00e7ue pour \u00eatre vue.</p>
      <div class="rs-specs reveal-elem">
        <div class="spec-item"><div class="spec-n">3A</div><div class="spec-l">Charge<br>rapide</div></div>
        <div class="spec-item"><div class="spec-n">90</div><div class="spec-l">cm de<br>longueur</div></div>
        <div class="spec-item"><div class="spec-n">5</div><div class="spec-l">coloris<br>disponibles</div></div>
      </div>
      <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-dark reveal-elem">Commander</a>
    </div>
    <div class="rs-right">
      <div class="rs-img-wrap reveal-elem">
        <?php
        $args = [ 'post_type' => 'product', 'posts_per_page' => 1, 'orderby' => 'date', 'order' => 'DESC' ];
        $loop = new WP_Query( $args );
        if ( $loop->have_posts() ) : $loop->the_post();
            if ( has_post_thumbnail() ) : the_post_thumbnail( 'chg-product-featured', [ 'class' => 'rs-product-img' ] );
            else : echo '<div class="rs-product-placeholder"><span>&#128225;</span></div>';
            endif;
        else :
            echo '<div class="rs-product-placeholder"><span>&#128225;</span></div>';
        endif;
        wp_reset_postdata();
        ?>
      </div>
    </div>
  </div>
</section>

<section class="slide-section" id="slideSection">
  <div class="slide-header">
    <div class="slide-tag">La Gamme</div>
    <h2 class="slide-title">Tous les produits</h2>
  </div>
  <div class="slide-track-wrap">
    <div class="slide-track" id="slideTrack">
      <?php
      $products = new WP_Query( [
        'post_type'      => 'product',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
      ] );
      if ( $products->have_posts() ) :
        while ( $products->have_posts() ) : $products->the_post();
          global $product;
          if ( ! $product ) $product = wc_get_product( get_the_ID() );
          $badge    = function_exists( 'chg_card_badge' ) ? chg_card_badge( $product ) : null;
          $price    = $product ? $product->get_price_html() : '';
          $img_url  = get_the_post_thumbnail_url( null, 'chg-product-card' );
      ?>
      <div class="slide-card" data-product-id="<?php echo get_the_ID(); ?>">
        <?php if ( $badge ) : ?>
          <div class="card-badge badge-<?php echo esc_attr( $badge['id'] ); ?>"><?php echo $badge['label']; ?></div>
        <?php endif; ?>
        <div class="slide-card-img">
          <?php if ( $img_url ) : ?>
            <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>" class="slide-product-img">
          <?php else : ?>
            <div class="card-placeholder-icon">&#128225;</div>
          <?php endif; ?>
        </div>
        <div class="slide-card-info">
          <div class="sc-name"><?php the_title(); ?></div>
          <div class="sc-price"><?php echo $price; ?></div>
          <a href="<?php the_permalink(); ?>" class="card-add">Voir</a>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata();
      else : ?>
        <div class="no-products-notice">
          <p>Aucun produit pour le moment.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="manifesto" id="manifesto">
  <div class="manifesto-inner">
    <p class="manifesto-line" id="manifestoLine"><?php echo esc_html( $mani_txt ); ?></p>
    <p class="manifesto-sub"><?php echo esc_html( $mani_sub ); ?></p>
  </div>
</section>

<section class="stats-section" id="stats">
  <div class="stats-grid">
    <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
    <div class="stat-item">
      <div class="stat-num">
        <?php echo esc_html( chg_option( "chg_stat_{$i}_num", '' ) ); ?>
        <span class="stat-suffix"><?php echo esc_html( chg_option( "chg_stat_{$i}_suffix", '' ) ); ?></span>
      </div>
      <div class="stat-unit"><?php echo esc_html( chg_option( "chg_stat_{$i}_unit", '' ) ); ?></div>
      <div class="stat-desc"><?php echo esc_html( chg_option( "chg_stat_{$i}_desc", '' ) ); ?></div>
    </div>
    <?php endfor; ?>
  </div>
</section>

<section class="nl-section" id="nl">
  <div class="nl-inner">
    <div class="nl-tag">Newsletter</div>
    <h2 class="nl-title">Restez inform\u00e9.</h2>
    <p class="nl-sub">Nouveaux coloris, drops exclusifs, offres abonn\u00e9s.</p>
    <?php
    $mc4wp_id = chg_option( 'chg_mc4wp_form_id', '' );
    if ( shortcode_exists( 'mc4wp_form' ) && $mc4wp_id ) {
      echo do_shortcode( '[mc4wp_form id="' . intval( $mc4wp_id ) . '"]' );
    } else { ?>
      <form class="nl-form" method="POST">
        <input type="email" name="email" class="nl-input" placeholder="votre@email.com" required>
        <button type="submit" class="btn-dark">S'inscrire</button>
      </form>
    <?php } ?>
  </div>
</section>

<?php get_footer(); ?>
