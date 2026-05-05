<?php
/**
 * Archive Blog — Chargeurie
 * Liste des articles, même charte que la home
 *
 * @package Chargeurie
 */
defined( 'ABSPATH' ) || exit;
get_header();

$paged       = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$is_category = is_category();
$is_tag      = is_tag();
$is_author   = is_author();
$is_date     = is_date();
?>

<!-- Hero -->
<section class="chg-blog-hero">
  <div class="chg-blog-hero-inner">
    <p class="chg-blog-hero-tag">
      <?php
      if ( $is_category )      echo esc_html__( 'Catégorie', 'chargeurie' );
      elseif ( $is_tag )       echo esc_html__( 'Tag', 'chargeurie' );
      elseif ( $is_author )    echo esc_html__( 'Auteur', 'chargeurie' );
      elseif ( $is_date )      echo esc_html__( 'Archives', 'chargeurie' );
      else                     echo esc_html__( 'Blog', 'chargeurie' );
      ?>
    </p>
    <h1 class="chg-blog-hero-title">
      <?php
      if ( is_home() || is_front_page() ) {
        echo esc_html__( 'Actualités', 'chargeurie' );
      } elseif ( $is_author ) {
        echo esc_html( get_the_author_meta( 'display_name', get_queried_object_id() ) );
      } else {
        single_term_title();
      }
      ?>
    </h1>
    <?php if ( $is_category && category_description() ) : ?>
      <p class="chg-blog-hero-desc"><?php echo wp_kses_post( category_description() ); ?></p>
    <?php endif; ?>
  </div>
</section>

<!-- Grille articles -->
<section class="chg-blog-wrap">
  <div class="chg-blog-container">

    <?php if ( have_posts() ) : ?>

      <!-- Article featured (premier uniquement sur page 1) -->
      <?php if ( $paged === 1 ) : the_post(); ?>
      <article <?php post_class( 'chg-post-featured' ); ?> itemscope itemtype="https://schema.org/BlogPosting">
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>" class="chg-post-featured-img" aria-hidden="true" tabindex="-1">
            <?php the_post_thumbnail( 'large', [ 'loading' => 'eager', 'itemprop' => 'image' ] ); ?>
          </a>
        <?php endif; ?>
        <div class="chg-post-featured-body">
          <div class="chg-post-meta">
            <?php
            $cats = get_the_category();
            if ( $cats ) :
              $cat = $cats[0];
            ?>
              <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="chg-post-cat">
                <?php echo esc_html( $cat->name ); ?>
              </a>
            <?php endif; ?>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="chg-post-date" itemprop="datePublished">
              <?php echo esc_html( get_the_date() ); ?>
            </time>
          </div>
          <h2 class="chg-post-featured-title" itemprop="headline">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <p class="chg-post-featured-excerpt" itemprop="description">
            <?php echo wp_trim_words( get_the_excerpt(), 30, '…' ); ?>
          </p>
          <a href="<?php the_permalink(); ?>" class="chg-post-featured-cta">
            <?php esc_html_e( 'Lire l’article', 'chargeurie' ); ?>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </article>
      <?php endif; ?>

      <!-- Grille articles secondaires -->
      <?php if ( have_posts() ) : ?>
      <div class="chg-posts-grid">
        <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( 'chg-post-card' ); ?> itemscope itemtype="https://schema.org/BlogPosting">
          <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>" class="chg-post-card-img" aria-hidden="true" tabindex="-1">
              <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'itemprop' => 'image' ] ); ?>
            </a>
          <?php else : ?>
            <a href="<?php the_permalink(); ?>" class="chg-post-card-img chg-post-card-img--empty" aria-hidden="true" tabindex="-1">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
            </a>
          <?php endif; ?>
          <div class="chg-post-card-body">
            <div class="chg-post-meta">
              <?php
              $cats = get_the_category();
              if ( $cats ) :
                $cat = $cats[0];
              ?>
                <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="chg-post-cat">
                  <?php echo esc_html( $cat->name ); ?>
                </a>
              <?php endif; ?>
              <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="chg-post-date" itemprop="datePublished">
                <?php echo esc_html( get_the_date() ); ?>
              </time>
            </div>
            <h2 class="chg-post-card-title" itemprop="headline">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <p class="chg-post-card-excerpt" itemprop="description">
              <?php echo wp_trim_words( get_the_excerpt(), 18, '…' ); ?>
            </p>
            <a href="<?php the_permalink(); ?>" class="chg-post-card-link" aria-label="<?php echo esc_attr( sprintf( __( 'Lire %s', 'chargeurie' ), get_the_title() ) ); ?>">
              <?php esc_html_e( 'Lire', 'chargeurie' ); ?>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          </div>
        </article>
        <?php endwhile; ?>
      </div>
      <?php endif; ?>

      <!-- Pagination -->
      <nav class="chg-blog-pagination" aria-label="<?php esc_attr_e( 'Navigation des articles', 'chargeurie' ); ?>">
        <?php
        echo paginate_links( [
          'prev_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>',
          'next_text' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>',
          'type'      => 'list',
        ] );
        ?>
      </nav>

    <?php else : ?>
      <div class="chg-blog-empty">
        <p><?php esc_html_e( 'Aucun article trouvé.', 'chargeurie' ); ?></p>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php get_footer(); ?>
