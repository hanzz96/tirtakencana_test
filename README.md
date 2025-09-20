# Laravel Product Catalog Test (Starter)

## ⚙️ Requirements

Sebelum menjalankan project ini, pastikan environment sesuai dengan versi berikut:

- **Node.js**: `22.14.0`
- **PHP**: `8.4.3`
- **Composer**: versi terbaru (disarankan)
- **MySQL**: versi terbaru

```bash
php artisan migrate:fresh --seed 
```

2. Jalankan di terminal 1 untuk Server Backend
```
php artisan serve
```

3. Jalankan di terminal 2 untuk Frontend
```
npm run dev
```

4. buka localhost:8000

## 📤 Upload Excel Guide

Untuk melakukan upload data via Excel, pastikan file memiliki **header** yang sesuai dengan nama kolom pada masing-masing tabel di database.

- Header wajib sama persis dengan field pada model & import class (`WithHeadingRow`).
- Contoh format Excel dapat dilihat pada direktori: app/Import/example/example_table_b.xlsx


# Menu Table A

![Preview](/demo_image/menu_a.png)

# Menu Table B

![Preview](/demo_image/menu_b.png)

# Menu Table C

![Preview](/demo_image/menu_c.png)

# Menu Table D

![Preview](/demo_image/menu_d.png)