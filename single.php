<?php
/**
 * Article unique — Chargeurie
 * Même charte que la home
 *
 * @package Chargeurie
 */
defined( 'ABSPATH' ) || exit;
get_header();

if ( ! have_posts() ) {
  wp_redirect( home_url( '/blog/' ) );
  exit;
}
the_post();

$cats        = get_the_category();
$cat         = $cats ? $cats[0] : null;
$read_time   = max( 1, round( str_word_count( strip_tags( get_the_content() ) ) / 200 ) );
$author_id   = get_the_author_meta( 'ID' );
$author_name = get_the_author_meta( 'display_name' );
$author_bio  = get_the_author_meta( 'description' );
?>

<!-- Hero article -->
<section class="chg-single-hero">
  <div class="chg-single-hero-inner">
    <!-- Breadcrumb -->
    <nav class="chg-single-breadcrumb" aria-label="Fil d'Ariane">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'chargeurie' ); ?></a>
      <span aria-hidden="true">/</span>
      <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'Blog', 'chargeurie' ); ?></a>
      <?php if ( $cat ) : ?>
        <span aria-hidden="true">/</span>
        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
      <?php endif; ?>
    </nav>

    <div class="chg-single-meta">
      <?php if ( $cat ) : ?>
        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="chg-post-cat">
          <?php echo esc_html( $cat->name ); ?>
        </a>
      <?php endif; ?>
      <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="chg-post-date">
        <?php echo esc_html( get_the_date() ); ?>
      </time>
      <span class="chg-post-readtime">
        <?php printf( esc_html__( '%d min de lecture', 'chargeurie' ), $read_time ); ?>
      </span>
    </div>

    <h1 class="chg-single-title"><?php the_title(); ?></h1>

    <?php if ( has_excerpt() ) : ?>
      <p class="chg-single-intro"><?php the_excerpt(); ?></p>
    <?php endif; ?>

    <!-- Auteur -->
    <div class="chg-single-author-mini">
      <?php echo get_avatar( $author_id, 40, '', $author_name, [ 'class' => 'chg-author-avatar' ] ); ?>
      <div>
        <span class="chg-author-by"><?php esc_html_e( 'Par', 'chargeurie' ); ?></span>
        <span class="chg-author-name"><?php echo esc_html( $author_name ); ?></span>
      </div>
    </div>
  </div>
</section>

<!-- Image à la une pleine largeur -->
<?php if ( has_post_thumbnail() ) : ?>
<div class="chg-single-cover">
  <?php the_post_thumbnail( 'full', [ 'loading' => 'eager', 'class' => 'chg-single-cover-img' ] ); ?>
</div>
<?php endif; ?>

<!-- Corps de l'article -->
<div class="chg-single-wrap">

  <!-- Contenu -->
  <article class="chg-single-content" itemprop="articleBody">
    <?php the_content(); ?>

    <?php
    wp_link_pages( [
      'before' => '<div class="chg-page-links"><span>' . esc_html__( 'Pages :', 'chargeurie' ) . '</span>',
      'after'  => '</div>',
    ] );
    ?>

    <!-- Tags -->
    <?php
    $tags = get_the_tags();
    if ( $tags ) : ?>
      <div class="chg-single-tags">
        <?php foreach ( $tags as $tag ) : ?>
          <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="chg-tag">
            #<?php echo esc_html( $tag->name ); ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Partage -->
    <div class="chg-single-share">
      <span class="chg-share-label"><?php esc_html_e( 'Partager', 'chargeurie' ); ?></span>
      <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" rel="noopener noreferrer" class="chg-share-btn" aria-label="Partager sur X">
        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
      </a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="chg-share-btn" aria-label="Partager sur Facebook">
        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.513c-1.491 0-1.956.93-1.956 1.887v2.266h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
      </a>
      <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="chg-share-btn" aria-label="Partager sur LinkedIn">
        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
      </a>
    </div>
  </article>

  <!-- Bio auteur -->
  <?php if ( $author_bio ) : ?>
  <aside class="chg-single-author-box">
    <?php echo get_avatar( $author_id, 72, '', $author_name, [ 'class' => 'chg-author-avatar-lg' ] ); ?>
    <div class="chg-author-box-body">
      <p class="chg-author-box-name"><?php echo esc_html( $author_name ); ?></p>
      <p class="chg-author-box-bio"><?php echo wp_kses_post( $author_bio ); ?></p>
    </div>
  </aside>
  <?php endif; ?>

  <!-- Navigation préc / suiv -->
  <nav class="chg-single-nav" aria-label="<?php esc_attr_e( 'Navigation entre articles', 'chargeurie' ); ?>">
    <?php
    $prev = get_previous_post();
    $next = get_next_post();
    if ( $prev ) : ?>
      <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="chg-single-nav-link chg-single-nav-link--prev">
        <span class="chg-single-nav-dir">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
          <?php esc_html_e( 'Précédent', 'chargeurie' ); ?>
        </span>
        <span class="chg-single-nav-title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
      </a>
    <?php endif;
    if ( $next ) : ?>
      <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="chg-single-nav-link chg-single-nav-link--next">
        <span class="chg-single-nav-dir">
          <?php esc_html_e( 'Suivant', 'chargeurie' ); ?>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </span>
        <span class="chg-single-nav-title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
      </a>
    <?php endif; ?>
  </nav>

  <!-- Articles liés -->
  <?php
  if ( $cat ) :
    $related = new WP_Query( [
      'category__in'   => [ $cat->term_id ],
      'post__not_in'   => [ get_the_ID() ],
      'posts_per_page' => 3,
      'orderby'        => 'rand',
    ] );
    if ( $related->have_posts() ) : ?>
      <section class="chg-single-related">
        <h2 class="chg-single-related-title"><?php esc_html_e( 'Articles similaires', 'chargeurie' ); ?></h2>
        <div class="chg-posts-grid chg-posts-grid--3">
          <?php while ( $related->have_posts() ) : $related->the_post(); ?>
            <article <?php post_class( 'chg-post-card' ); ?>>
              <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>" class="chg-post-card-img" aria-hidden="true" tabindex="-1">
                  <?php the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] ); ?>
                </a>
              <?php else : ?>
                <a href="<?php the_permalink(); ?>" class="chg-post-card-img chg-post-card-img--empty" aria-hidden="true" tabindex="-1">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                </a>
              <?php endif; ?>
              <div class="chg-post-card-body">
                <div class="chg-post-meta">
                  <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="chg-post-date">
                    <?php echo esc_html( get_the_date() ); ?>
                  </time>
                </div>
                <h3 class="chg-post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <a href="<?php the_permalink(); ?>" class="chg-post-card-link">
                  <?php esc_html_e( 'Lire', 'chargeurie' ); ?>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
              </div>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      </section>
    <?php endif;
  endif;
  ?>

</div><!-- .chg-single-wrap -->

<?php get_footer(); ?>
