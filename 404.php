<?php get_header(); ?>
<main class="chg-main chg-container chg-404">
  <div class="error-404-content">
    <div class="error-num">404</div>
    <h1 class="error-title">Page introuvable</h1>
    <p class="error-sub">La page que vous cherchez n'existe pas ou a été déplacée.</p>
    <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn-dark">Retour à l'accueil</a>
  </div>
</main>
<?php get_footer(); ?>
