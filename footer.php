<?php /**
 * Chargeurie — footer.php
 */ ?>
<footer class="site-footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="f-brand"><?php echo esc_html( get_bloginfo('name') ); ?></div>
      <div class="f-sub">Câbles &amp; Accessoires de Charge</div>
      <p class="f-about">Des accessoires de charge pensés pour s'intégrer à votre vie, pas pour être rangés dans un tiroir.</p>
      <div class="f-soc">
        <?php foreach ( [ 'Instagram' => 'chg_instagram_url', 'TikTok' => 'chg_tiktok_url', 'LinkedIn' => 'chg_linkedin_url' ] as $label => $key ) :
          $url = chg_option( $key, '#' ); if ( $url ) : ?>
          <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener"><?php echo $label; ?></a>
        <?php endif; endforeach; ?>
      </div>
    </div>
    <?php foreach ( [ 'footer_1' => 'Produits', 'footer_2' => 'Informations', 'footer_3' => 'Service Client' ] as $loc => $label ) : ?>
    <div class="f-col">
      <h5><?php echo esc_html($label); ?></h5>
      <?php wp_nav_menu( [ 'theme_location' => $loc, 'container' => false, 'fallback_cb' => '__return_false' ] ); ?>
    </div>
    <?php endforeach; ?>
  </div>
</footer>
<div class="f-bottom">
  <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> — Tous droits réservés</p>
  <div class="pay-row"><span class="pay">Visa</span><span class="pay">Mastercard</span><span class="pay">PayPal</span><span class="pay">Apple Pay</span></div>
</div>
<?php wp_footer(); ?>
</body>
</html>
