<?php
/**
 * Chargeurie — single.php
 */
get_header();
?>
<main class="chg-main chg-container">
  <?php while ( have_posts() ) : the_post(); ?>
    <article <?php post_class( 'chg-single' ); ?>>
      <header class="single-header">
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="single-hero-img"><?php the_post_thumbnail( 'chg-hero' ); ?></div>
        <?php endif; ?>
        <div class="single-meta">
          <span class="single-date"><?php echo get_the_date(); ?></span>
          <span class="single-sep">&middot;</span>
          <?php the_category( ' &middot; ' ); ?>
        </div>
        <h1 class="single-title"><?php the_title(); ?></h1>
      </header>
      <div class="single-content entry-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
