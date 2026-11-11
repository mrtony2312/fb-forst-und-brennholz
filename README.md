# F & B Forst und Brennholz — Boutique en ligne

Boutique e-commerce Laravel pour **F & B Forst und Brennholz GmbH**, vendeur de bois de chauffage (Brennholz), granulés de bois (Pellets), briquettes et bois densifié, basé à Hohenfels (Bavière, Allemagne).

Le site propose la vente en ligne de combustibles bois avec livraison **gratuite en Allemagne en 2 à 5 jours ouvrés**.

## Stack technique

- **Backend** : Laravel 13 (PHP 8.4+)
- **Frontend** : Blade + Tailwind CSS, Vite
- **Base de données** : MySQL/MariaDB (Eloquent ORM)

## Structure fonctionnelle

- **Catalogue / Shop** — `App\Http\Controllers\ShopController`, `ProductController` : liste, recherche, catégories, fiche produit.
- **Panier** — `App\Http\Controllers\CartController` + `App\Services\Cart` : ajout, mise à jour, suppression, calcul du sous-total (livraison gratuite par défaut).
- **Commande / Checkout** — `App\Http\Controllers\CheckoutController`, `App\Models\Order` : validation de commande, confirmation, e-mails de notification (client + admin).
- **Favoris** — `App\Http\Controllers\WishlistController`.
- **Pages institutionnelles** — `App\Http\Controllers\PageController` : à propos, contact, FAQ, Impressum, Datenschutz (confidentialité), AGB (CGV).

## Installation locale

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed

npm run build   # ou npm run dev en développement
php artisan serve
```

## Configuration importante (`.env`)

- `APP_NAME`, `APP_URL` — identité et URL du site.
- `MAIL_*` — SMTP utilisé pour l'envoi de la confirmation de commande au client et de la notification à l'équipe (`MAIL_ADMIN_ADDRESS`).
- `DB_*` — connexion à la base de données.

⚠️ Ne jamais committer de vrais identifiants (mots de passe SMTP, clés API) dans `.env.example` : ce fichier est un modèle destiné à être partagé publiquement.

## Informations légales de l'entreprise

- **Raison sociale** : F & B Forst und Brennholz GmbH
- **Adresse** : Dekan-Schnitter-Straße 4, 92366 Hohenfels, Allemagne
- **Registergericht** : Amtsgericht Nürnberg
- **N° registre** : HRB 40744
- **Capital** : 25 100 €
- Ces informations sont affichées sur les pages `/impressum`, `/agb` et `/datenschutz`, et doivent être tenues à jour en cas de changement (adresse, dirigeants, forme juridique, USt-IdNr.).

## Politique de livraison (marché DE)

- Livraison **gratuite en Allemagne**, sans montant minimum (`App\Services\Cart::shipping()` = `0.0`).
- Checkout force le pays `DE` (vendeur NAP = GmbH allemande F & B).
- Délai annoncé storefront + GMC : **2 à 5 jours ouvrés** (`config/merchant.php` → `min_days` / `max_days`, affiché via `shipping_summary()`).
- Handling feed GMC : `handling_min` / `handling_max` dans `config/merchant.php` (source de vérité pour le feed XML/TSV).
- Prix catalogue **TTC en EUR** (MwSt DE 19 %).

Toute modification doit être répercutée **simultanément** dans :
`config/merchant.php`, `config/shop.php`, `config/feed.php`, `App\Services\Cart`, pages `/versand`, `/agb`, `/faq`, `/rueckgabe`, bannière `layouts/omniva.blade.php`, feed Merchant et JSON-LD produit.

## Conformité Google Merchant Center

- Marché feed : **DE / de / EUR**, livraison gratuite `0.00 EUR`.
- Pages politiques crawlables : `/impressum`, `/versand`, `/rueckgabe`, `/agb`, `/datenschutz`, `/kontakt`.
- Feeds : `/feeds/google-merchant-de.xml` (+ TSV), jobs de refresh/sync.
- JSON-LD produit sur la fiche (`GoogleProductData::toJsonLd()`).
