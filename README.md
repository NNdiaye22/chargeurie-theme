# Chargeurie Theme

Thème WordPress premium pour Chargeurie, optimisé WooCommerce et GSAP ScrollTrigger.

## Structure

```
chargeurie-theme/
├── style.css                    # En-tête WordPress
├── functions.php                # Setup thème, helpers, WooCommerce
├── header.php / footer.php      # En-tête et pied de page
├── front-page.php               # Homepage avec animations GSAP
├── page.php / single.php        # Templates génériques
├── 404.php                      # Page 404
├── page-cart.php                # Panier WooCommerce
├── page-checkout.php            # Commande
├── page-myaccount.php           # Mon compte
├── page-contact.php             # Formulaire de contact
├── page-privacy-policy.php      # Mentions légales
├── inc/
│   ├── enqueue.php              # Chargement GSAP + assets
│   ├── customizer.php           # Options Customizer WordPress
│   └── woocommerce.php          # Hooks & overrides WooCommerce
├── assets/
│   ├── css/main.css             # Styles principaux
│   ├── css/woocommerce.css      # Override styles WooCommerce
│   ├── css/wc-pages.css         # Styles pages WC (panier, compte)
│   └── js/main.js               # Animations GSAP ScrollTrigger
└── woocommerce/
    ├── archive-product.php      # Override page boutique
    ├── content-product.php      # Override carte produit
    └── single-product.php       # Override fiche produit
```

## Installation

1. Uploader le dossier `chargeurie-theme` dans `wp-content/themes/`.
2. Activer le thème dans **Apparence > Thèmes**.
3. Installer et activer **WooCommerce**.
4. Aller dans **Apparence > Menus** pour configurer :
   - Menu Principal
   - Footer — Produits
   - Footer — Informations
   - Footer — Service Client
5. Personnaliser dans **Apparence > Personnaliser > Chargeurie — Thème**.

## Personnalisation Customizer

| Section | Options disponibles |
|---------|--------------------|
| Identité | Tagline, ticker, URLs réseaux sociaux |
| Hero | Ligne 1/2/3, sous-titre, texte CTA |
| Manifeste | Phrase principale, sous-texte |
| Couleurs | Couleur accent (bleu) |
| Stats | 4 statistiques (chiffre, unité, suffixe, description) |

## Dépendances

- WordPress 6.0+
- WooCommerce (dernier stable)
- GSAP 3.12.5 + ScrollTrigger (chargés via CDN jsdelivr)

## Créé par

**2N — Ndiogou Ndiaye** · [github.com/NNdiaye22](https://github.com/NNdiaye22)
