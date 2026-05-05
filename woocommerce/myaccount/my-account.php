<?php
/**
 * Mon Compte — Chargeurie
 * Override de woocommerce/myaccount/my-account.php
 *
 * @package Chargeurie
 */
defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
$endpoint     = WC()->query->get_current_endpoint();
$endpoint     = $endpoint ?: 'dashboard';
?>

<!-- Hero Mon Compte -->
<section class="chg-account-hero">
  <div class="chg-account-hero-inner">
    <p class="chg-account-hero-tag"><?php esc_html_e( 'Mon espace', 'chargeurie' ); ?></p>
    <h1>
      <?php
      $firstname = $current_user->first_name ?: $current_user->display_name;
      printf(
        /* translators: %s: prénom */
        esc_html__( 'Bonjour, %s', 'chargeurie' ),
        esc_html( $firstname )
      );
      ?>
    </h1>
  </div>
</section>

<!-- Corps -->
<div class="chg-account-wrap">

  <!-- Sidebar navigation -->
  <aside class="chg-account-sidebar">
    <nav class="chg-account-nav" aria-label="<?php esc_attr_e( 'Navigation compte', 'chargeurie' ); ?>">
      <?php
      $menu_items = wc_get_account_menu_items();
      $icons = [
        'dashboard'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
        'orders'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>',
        'edit-address'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10A8 8 0 0 0 4 12c0 6 8 10 8 10z"/><circle cx="12" cy="12" r="3"/></svg>',
        'edit-account'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        'customer-logout' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
      ];
      foreach ( $menu_items as $slug => $label ) :
        $is_active = wc_get_account_endpoint_url( $slug ) === trailingslashit( get_permalink() . $endpoint . '/' )
                     || ( $endpoint === $slug )
                     || ( $slug === 'dashboard' && $endpoint === 'dashboard' );
        $icon = $icons[ $slug ] ?? '';
      ?>
      <a href="<?php echo esc_url( wc_get_account_endpoint_url( $slug ) ); ?>"
         class="chg-account-nav-item<?php echo wc_get_account_menu_item_classes( $slug ) ? ' ' . esc_attr( wc_get_account_menu_item_classes( $slug ) ) : ''; ?>"
         <?php if ( $slug === 'customer-logout' ) echo 'data-logout="true"'; ?>>
        <?php if ( $icon ) : ?>
          <span class="chg-account-nav-icon" aria-hidden="true"><?php echo $icon; // phpcs:ignore ?></span>
        <?php endif; ?>
        <span class="chg-account-nav-label"><?php echo esc_html( $label ); ?></span>
        <?php if ( $slug === 'orders' ) :
          $count = wc_get_customer_order_count( get_current_user_id() );
          if ( $count > 0 ) : ?>
            <span class="chg-account-nav-badge"><?php echo (int) $count; ?></span>
          <?php endif;
        endif; ?>
      </a>
      <?php endforeach; ?>
    </nav>
  </aside>

  <!-- Contenu principal -->
  <main class="chg-account-content">
    <?php woocommerce_output_all_notices(); ?>
    <?php woocommerce_account_content(); ?>
  </main>

</div><!-- .chg-account-wrap -->
