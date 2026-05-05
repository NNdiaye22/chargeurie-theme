<?php
/**
 * Panier — Chargeurie
 * Override de woocommerce/cart/cart.php
 *
 * @package Chargeurie
 */
defined( 'ABSPATH' ) || exit;
?>

<?php woocommerce_output_all_notices(); ?>

<?php if ( WC()->cart->is_empty() ) : ?>

  <div class="chg-container-xl" style="padding: 8rem 0;">
    <div style="background:var(--white);border-radius:var(--radius-lg);padding:5rem 2rem;text-align:center;">
      <p style="font-size:1rem;color:var(--stone);margin-bottom:1.5rem;"><?php esc_html_e( 'Votre panier est vide.', 'woocommerce' ); ?></p>
      <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"
         class="wc-backward" style="display:inline-block;background:var(--blue);color:var(--white);border-radius:999px;padding:.9rem 2.2rem;font-size:.6rem;letter-spacing:.1em;font-weight:700;text-transform:uppercase;text-decoration:none;transition:background .25s;">
        <?php esc_html_e( 'Continuer vos achats', 'woocommerce' ); ?>
      </a>
    </div>
  </div>

<?php else : ?>

  <!-- Hero panier -->
  <section class="chg-cart-hero">
    <div class="chg-cart-hero-inner">
      <p class="chg-cart-hero-tag"><?php esc_html_e( 'Mon panier', 'chargeurie' ); ?></p>
      <h1><?php
        $count = WC()->cart->get_cart_contents_count();
        printf(
          _n( '%d article', '%d articles', $count, 'chargeurie' ),
          $count
        );
      ?></h1>
    </div>
  </section>

  <!-- Corps -->
  <div class="chg-cart-wrap">

    <!-- Colonne gauche : tableau produits -->
    <div>
      <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

        <div class="chg-cart-items">
          <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">

            <thead>
              <tr>
                <th class="product-remove"></th>
                <th class="product-thumbnail"></th>
                <th class="product-name"><?php esc_html_e( 'Produit', 'woocommerce' ); ?></th>
                <th class="product-price"><?php esc_html_e( 'Prix', 'woocommerce' ); ?></th>
                <th class="product-quantity"><?php esc_html_e( 'Quantité', 'woocommerce' ); ?></th>
                <th class="product-subtotal"><?php esc_html_e( 'Sous-total', 'woocommerce' ); ?></th>
              </tr>
            </thead>

            <tbody>
              <?php do_action( 'woocommerce_before_cart_contents' ); ?>

              <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
                  $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
              ?>
              <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                <!-- Supprimer -->
                <td class="product-remove">
                  <?php echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    'woocommerce_cart_item_remove_link',
                    sprintf(
                      '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                      esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                      esc_attr__( 'Supprimer cet article', 'woocommerce' ),
                      esc_attr( $product_id ),
                      esc_attr( $_product->get_sku() )
                    ),
                    $cart_item_key
                  ); ?>
                </td>

                <!-- Miniature -->
                <td class="product-thumbnail">
                  <?php $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                    if ( $product_permalink ) echo '<a href="' . esc_url( $product_permalink ) . '">' . $thumbnail . '</a>';
                    else echo $thumbnail; // phpcs:ignore
                  ?>
                </td>

                <!-- Nom -->
                <td class="product-name" data-title="<?php esc_attr_e( 'Produit', 'woocommerce' ); ?>">
                  <?php if ( $product_permalink ) : ?>
                    <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></a>
                  <?php else : ?>
                    <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?>
                  <?php endif; ?>
                  <?php do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key ); ?>
                  <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore ?>
                  <?php if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) : ?>
                    <p class="backorder_notification"><?php esc_html_e( 'Disponible en commande', 'woocommerce' ); ?></p>
                  <?php endif; ?>
                </td>

                <!-- Prix unit -->
                <td class="product-price" data-title="<?php esc_attr_e( 'Prix', 'woocommerce' ); ?>">
                  <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore ?>
                </td>

                <!-- Quantité -->
                <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantité', 'woocommerce' ); ?>">
                  <?php if ( $_product->is_sold_individually() ) :
                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                  else :
                    $product_quantity = woocommerce_quantity_input(
                      [
                        'input_name'   => "cart[{$cart_item_key}][qty]",
                        'input_value'  => $cart_item['quantity'],
                        'max_value'    => $_product->get_max_purchase_quantity(),
                        'min_value'    => '0',
                        'product_name' => $_product->get_name(),
                      ],
                      $_product,
                      false
                    );
                  endif;
                  echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore ?>
                </td>

                <!-- Sous-total -->
                <td class="product-subtotal" data-title="<?php esc_attr_e( 'Sous-total', 'woocommerce' ); ?>">
                  <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore ?>
                </td>

              </tr>
              <?php endif; endforeach; ?>

              <?php do_action( 'woocommerce_cart_contents' ); ?>

              <!-- Actions : coupon + mise à jour -->
              <tr>
                <td colspan="6" class="actions">
                  <div class="coupon">
                    <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Code promo :', 'woocommerce' ); ?></label>
                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Code promo', 'woocommerce' ); ?>" />
                    <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Appliquer', 'woocommerce' ); ?>">
                      <?php esc_html_e( 'Appliquer', 'woocommerce' ); ?>
                    </button>
                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                  </div>
                  <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Mettre à jour', 'woocommerce' ); ?>">
                    <?php esc_html_e( 'Mettre à jour', 'woocommerce' ); ?>
                  </button>
                  <?php do_action( 'woocommerce_cart_actions' ); ?>
                  <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </td>
              </tr>

              <?php do_action( 'woocommerce_after_cart_contents' ); ?>
            </tbody>
          </table>
        </div><!-- .chg-cart-items -->

      </form>

      <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
    </div><!-- col gauche -->

    <!-- Colonne droite : récapitulatif & totaux -->
    <div class="chg-cart-summary">
      <p class="chg-cart-summary-header"><?php esc_html_e( 'Récapitulatif', 'chargeurie' ); ?></p>

      <?php do_action( 'woocommerce_cart_collaterals' ); ?>

      <!-- Trust strip -->
      <ul class="chg-cart-trust">
        <li class="chg-cart-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Paiement sécurisé
        </li>
        <li class="chg-cart-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8zM5 18a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm14 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>
          Livraison rapide
        </li>
        <li class="chg-cart-trust-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          Retours gratuits 30j
        </li>
      </ul>
    </div><!-- .chg-cart-summary -->

  </div><!-- .chg-cart-wrap -->

<?php endif; ?>
