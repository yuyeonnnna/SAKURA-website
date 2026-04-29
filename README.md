# SAKURA — PHP + MySQL Edition

Fancy & Accessories store. Static frontend (HTML/CSS/Bootstrap) wired to a PHP + MySQL backend.

## 🚀 Setup in XAMPP (5 minutes)

1. **Copy** the `sakura-php` folder into `C:\xampp\htdocs\` (or your XAMPP `htdocs` path).
2. **Start** Apache + MySQL from the XAMPP control panel.
3. **Open phpMyAdmin** → http://localhost/phpmyadmin
4. Click **Import**, choose `database/sakura.sql`, click **Go**.
   - This creates the `sakura_db` database, all tables, 24 products, 1 admin + 2 sample customers, and 3 sample orders.
5. Visit **http://localhost/sakura-php/**

If your MySQL user/password is different from XAMPP's defaults, edit `includes/config.php`:
```php
define('DB_USER', 'root');
define('DB_PASS', '');
```

## 🔑 Demo accounts

| Role     | Email                  | Password   |
|----------|------------------------|------------|
| Admin    | admin@sakura.local     | sakura123  |
| Customer | su@example.com         | password   |
| Customer | hnin@example.com       | password   |

> Passwords are stored plaintext (per project setting — demo only). For real use, switch to `password_hash()`.

## 📂 What's included

**Customer pages:** index · shop · product · cart · checkout · order-success · login (sign in / sign up) · forgot-password · reset-password · account · orders (with monthly filter) · about · contact · location (with Google Map) · faq · size-guide

**Burmese policy pages (မြန်မာ):** privacy · delivery-policy · terms

**Admin (admin-login.php → admin.php):**
- Dashboard with revenue / orders / products / users stats
- Products: add / edit / delete (modal form)
- Orders: view all + change status (Pending → Paid → Shipped → Delivered → Cancelled)
- Messages: view all contact form submissions

## 💰 Currency & shipping

- All prices in **MMK** (Myanmar Kyat), 40,000 – 100,000 range.
- Flat 5,000 MMK shipping; **free over 200,000 MMK**.

## 🗂 File structure

```
sakura-php/
├── index.php / shop.php / product.php / cart.php / checkout.php …
├── admin.php / admin-login.php
├── includes/
│   ├── config.php          ← DB credentials live here
│   ├── header.php / footer.php
│   └── db_helpers.php
├── api/cart-count.php      ← used by JS cart badge
├── database/sakura.sql     ← import this in phpMyAdmin
├── css/style.css
├── js/cart.js / main.js
└── images/ (26 product images)
```

Made with ♡ in Yangon.
