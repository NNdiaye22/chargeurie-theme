<?php
/**
 * Chargeurie — page-contact.php
 * Template Name: Contact
 */
get_header();
?>
<main class="chg-main chg-container chg-contact">
  <header class="page-header">
    <div class="page-header-tag">Contact</div>
    <h1 class="page-header-title">Nous écrire</h1>
    <p class="page-header-sub">Une question sur votre commande, un problème technique ou une simple curiosité — on vous répond sous 24h.</p>
  </header>
  <div class="contact-grid">
    <div class="contact-form-wrap">
      <?php
      if ( shortcode_exists( 'contact-form-7' ) ) {
          $cf7_id = chg_option( 'chg_cf7_contact_id', '' );
          echo $cf7_id ? do_shortcode( '[contact-form-7 id="' . intval( $cf7_id ) . '"]' ) : '<p>Configurez l\'ID du formulaire CF7 dans le Customizer.</p>';
      } elseif ( shortcode_exists( 'wpforms' ) ) {
          $wpf_id = chg_option( 'chg_wpforms_contact_id', '' );
          echo $wpf_id ? do_shortcode( '[wpforms id="' . intval( $wpf_id ) . '"]' ) : '<p>Configurez l\'ID du formulaire WPForms dans le Customizer.</p>';
      } else {
          echo '<p>Installez <strong>Contact Form 7</strong> ou <strong>WPForms</strong> pour activer ce formulaire.</p>';
      }
      ?>
    </div>
    <div class="contact-info">
      <div class="contact-info-item">
        <div class="ci-label">Email</div>
        <div class="ci-val"><?php echo esc_html( chg_option( 'chg_contact_email', 'contact@chargeurie.fr' ) ); ?></div>
      </div>
      <div class="contact-info-item">
        <div class="ci-label">Réseaux sociaux</div>
        <div class="ci-val">
          <a href="<?php echo esc_url( chg_option( 'chg_instagram_url', '#' ) ); ?>" target="_blank" rel="noopener">Instagram</a> &middot;
          <a href="<?php echo esc_url( chg_option( 'chg_tiktok_url', '#' ) ); ?>" target="_blank" rel="noopener">TikTok</a>
        </div>
      </div>
      <div class="contact-info-item">
        <div class="ci-label">Délai de réponse</div>
        <div class="ci-val">24 heures ouvrées</div>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>
