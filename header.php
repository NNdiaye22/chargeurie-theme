<?php
/**
 * Chargeurie — header.php
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="pbar"></div>

<!-- MOBILE MENU OVERLAY -->
<div class="mobile-menu" id="mobileMenu" aria-hidden="true" aria-label="Menu principal">
  <div class="mobile-menu-inner">
    <ul class="mobile-links">
      <?php
      $shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#';
      ?>
      <li><a href="<?php echo esc_url( $shop_url ); ?>">Produits</a></li>
      <li><a href="#reveal">La Lanière</a></li>
      <li><a href="#manifesto">Notre ADN</a></li>
      <li><a href="#nl">Contact</a></li>
    </ul>
    <a href="<?php echo esc_url( $shop_url ); ?>" class="mobile-cta btn-dark">Commander &rarr;</a>
    <div class="mobile-meta">USB-C &middot; 60W &middot; France</div>
  </div>
  <button class="mobile-close" id="mobileClose" aria-label="Fermer le menu">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
      <line x1="18" y1="6" x2="6" y2="18"/>
      <line x1="6" y1="6" x2="18" y2="18"/>
    </svg>
  </button>
</div>

<nav id="nav">
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
    <?php chg_the_logo(); ?>
  </a>

  <?php
  wp_nav_menu( [
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'nav-links',
    'fallback_cb'    => function() { ?>
      <ul class="nav-links">
        <li><a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' ); ?>">Produits</a></li>
        <li><a href="#reveal">La lanière</a></li>
        <li><a href="#nl">Contact</a></li>
      </ul>
    <?php }
  ] );
  ?>

  <div class="nav-right">
    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
      <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="nav-cart" aria-label="Panier">
        <span class="cart-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 01-8 0"/>
          </svg>
        </span>
        <?php $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
        <?php if ( $count > 0 ) : ?>
          <span class="cart-count" id="cartCount"><?php echo intval( $count ); ?></span>
        <?php else : ?>
          <span class="cart-count" id="cartCount" style="display:none;">0</span>
        <?php endif; ?>
      </a>
    <?php endif; ?>
    <a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' ); ?>" class="nav-cta">Commander</a>

    <!-- BURGER -->
    <button class="burger" id="burgerBtn" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobileMenu">
      <span class="burger-line"></span>
      <span class="burger-line"></span>
      <span class="burger-line"></span>
    </button>
  </div>
</nav>
