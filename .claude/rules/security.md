# Persyaratan Keamanan

- **Variabel Lingkungan:** Jangan pernah menulis hardcode kredensial database atau API keys. Selalu gunakan file `.env`.
- **Validasi Input:** Selalu gunakan *Form Request Validation* (`php artisan make:request`) atau `$request->validate()` untuk memvalidasi data masuk di Controller.
- **ORM Security:** Manfaatkan Eloquent untuk mencegah SQL Injection secara otomatis.