<?php
/**
 * Chargeurie — page.php
 */
get_header();
?>
<main class="chg-main chg-container">
  <?php while ( have_posts() ) : the_post(); ?>
    <article <?php post_class( 'chg-page-content' ); ?>>
      <header class="page-header">
        <div class="page-header-tag">Page</div>
        <h1 class="page-header-title"><?php the_title(); ?></h1>
      </header>
      <div class="page-body entry-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
