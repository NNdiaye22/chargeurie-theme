<?php get_header(); ?>
<main class="chg-main chg-container">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article <?php post_class('chg-post'); ?>><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php the_excerpt(); ?></article>
  <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
