<?php
/**
 * Chargeurie — page-privacy-policy.php
 * Template Name: Politique de confidentialité
 */
get_header();
?>
<main class="chg-main chg-container chg-legal">
  <header class="page-header">
    <div class="page-header-tag">Légal</div>
    <h1 class="page-header-title"><?php the_title(); ?></h1>
    <p class="page-header-meta">Dernière mise à jour : <?php echo get_the_modified_date(); ?></p>
  </header>
  <div class="legal-content entry-content">
    <?php the_content(); ?>
  </div>
</main>
<?php get_footer(); ?>
