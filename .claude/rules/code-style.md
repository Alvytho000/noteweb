# Panduan Gaya Kode Laravel

- **Standar Kode:** Patuhi standar PSR-12 untuk PHP.
- **Arsitektur:** Ikuti konvensi standar MVC Laravel. Letakkan logika bisnis utama di Controller atau Service Classes, dan query database di Model (Eloquent).
- **Penamaan:** 
  - Model & Controller: PascalCase (`NoteController`, `Note`).
  - Route & Variabel: camelCase atau snake_case secara konsisten.
- **Frontend:** Gunakan komponen vanilla JS/TS atau sesuaikan dengan framework UI yang terpasang di `resources/js/`.