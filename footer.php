<?php
/**
 * Chargeurie — footer.php
 */
?>

<footer class="site-footer">
  <div class="footer-grid">

    <div class="footer-brand">
      <div class="f-brand"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>
      <div class="f-sub">Câbles &amp; Accessoires de Charge</div>
      <p class="f-about">Des accessoires de charge pensés pour s'intégrer à votre vie, pas pour être rangés dans un tiroir.</p>
      <div class="f-soc">
        <?php $ig = chg_option( 'chg_instagram_url', '#' ); ?>
        <?php $tk = chg_option( 'chg_tiktok_url', '#' ); ?>
        <?php $li = chg_option( 'chg_linkedin_url', '#' ); ?>
        <?php if ( $ig && $ig !== '#' ) : ?><a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
        <?php if ( $tk && $tk !== '#' ) : ?><a href="<?php echo esc_url( $tk ); ?>" target="_blank" rel="noopener">TikTok</a><?php endif; ?>
        <?php if ( $li && $li !== '#' ) : ?><a href="<?php echo esc_url( $li ); ?>" target="_blank" rel="noopener">LinkedIn</a><?php endif; ?>
      </div>
    </div>

    <?php
    $footer_nav_labels = [
        'footer_1' => 'Produits',
        'footer_2' => 'Informations',
        'footer_3' => 'Service Client',
    ];
    foreach ( $footer_nav_labels as $location => $label ) :
    ?>
    <div class="f-col">
      <h5><?php echo esc_html( $label ); ?></h5>
      <?php
      wp_nav_menu( [
        'theme_location' => $location,
        'container'      => false,
        'menu_class'     => '',
        'fallback_cb'    => function() use ( $location ) {
          $fallbacks = [
            'footer_1' => [ 'Lanières USB-C' => '#', 'Câbles Bracelets' => '#', 'Câbles 4-en-1' => '#', 'Accessoires' => '#' ],
            'footer_2' => [ 'À propos' => '#', 'Guide d\'achat' => '#', 'Blog' => '#', 'Contact' => '#' ],
            'footer_3' => [ 'Livraison' => '#', 'Retours' => '#', 'FAQ' => '#', 'CGV' => '#' ],
          ];
          echo '<ul>';
          foreach ( $fallbacks[ $location ] ?? [] as $text => $url ) {
            echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $text ) . '</a></li>';
          }
          echo '</ul>';
        },
      ] );
      ?>
    </div>
    <?php endforeach; ?>

  </div>
</footer>

<div class="f-bottom">
  <p>&copy; <?php echo date( 'Y' ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?> — Tous droits réservés</p>
  <p class="f-credit">Site créé par <a href="https://www.buurdigital.com" target="_blank" rel="noopener noreferrer">BUUR Digital</a></p>
  <div class="pay-row">
    <span class="pay">Visa</span>
    <span class="pay">Mastercard</span>
    <span class="pay">PayPal</span>
    <span class="pay">Apple Pay</span>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
