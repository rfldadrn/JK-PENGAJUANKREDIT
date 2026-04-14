-- Insert Missing Demo Users
-- Copy dan jalankan via phpMyAdmin jika user demo belum lengkap

INSERT INTO tb_users (nama_lengkap, email, password, no_hp, role, status_akun, created_at, updated_at) VALUES
('Petugas AO', 'petugas@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'petugas', 'aktif', NOW(), NOW()),
('Analis Kredit', 'analis@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567892', 'analis', 'aktif', NOW(), NOW()),
('Pimpinan Cabang', 'pimpinan@bri.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567893', 'pimpinan', 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE email = email;

-- Verifikasi
SELECT id_user, nama_lengkap, email, role, status_akun FROM tb_users ORDER BY role;

-- Password untuk semua user: password
