<?php

class Laporan extends Model
{
    protected $table = 'tb_users';

    /**
     * Get laporan data nasabah
     */
    public function getLaporanNasabah($filters = [])
    {
        $query = "SELECT 
            u.id_user,
            u.nama_lengkap,
            u.email,
            u.no_hp as no_telepon,
            n.no_nik as nik,
            COALESCE(n.alamat_domisili, n.alamat_ktp) as alamat,
            n.pekerjaan,
            n.penghasilan_bulanan as penghasilan_per_bulan,
            u.created_at,
            COUNT(pk.id_pengajuan) as total_pengajuan,
            SUM(CASE WHEN ps.keputusan = 'disetujui' THEN 1 ELSE 0 END) as pengajuan_disetujui
        FROM tb_users u
        LEFT JOIN tb_nasabah n ON u.id_user = n.id_user
        LEFT JOIN tb_pengajuan_kredit pk ON n.id_nasabah = pk.id_nasabah
        LEFT JOIN tb_persetujuan ps ON pk.id_pengajuan = ps.id_pengajuan
        WHERE u.role = 'nasabah'";

        $params = [];

        if (!empty($filters['start_date'])) {
            $query .= " AND DATE(u.created_at) >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= " AND DATE(u.created_at) <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        if (!empty($filters['pekerjaan'])) {
            $query .= " AND n.pekerjaan LIKE :pekerjaan";
            $params['pekerjaan'] = '%' . $filters['pekerjaan'] . '%';
        }

        if (!empty($filters['search'])) {
            $query .= " AND (u.nama_lengkap LIKE :search OR n.no_nik LIKE :search2 OR u.email LIKE :search3)";
            $params['search'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
            $params['search3'] = '%' . $filters['search'] . '%';
        }

        $query .= " GROUP BY u.id_user, u.nama_lengkap, u.email, u.no_hp, n.no_nik, n.alamat_domisili, n.alamat_ktp, n.pekerjaan, n.penghasilan_bulanan, u.created_at";
        $query .= " ORDER BY u.created_at DESC";

        $stmt = $this->query($query, $params);
        return $stmt->fetchAll();
    }

    /**
     * Get laporan data pengajuan kredit
     */
    public function getLaporanPengajuan($filters = [])
    {
        $query = "SELECT 
            pk.id_pengajuan,
            pk.no_pengajuan,
            u.nama_lengkap as nama_nasabah,
            jk.nama_kredit as nama_produk,
            pk.jumlah_pinjaman,
            pk.tenor,
            pk.tujuan_kredit,
            pk.status_pengajuan,
            pk.tanggal_pengajuan,
            ps.keputusan,
            ps.tanggal_keputusan,
            ps.alasan_keputusan
        FROM tb_pengajuan_kredit pk
        INNER JOIN tb_nasabah n ON pk.id_nasabah = n.id_nasabah
        INNER JOIN tb_users u ON n.id_user = u.id_user
        INNER JOIN tb_jenis_kredit jk ON pk.id_jenis_kredit = jk.id_jenis_kredit
        LEFT JOIN tb_persetujuan ps ON pk.id_pengajuan = ps.id_pengajuan
        WHERE pk.status_pengajuan != 'draft'";

        $params = [];

        if (!empty($filters['start_date'])) {
            $query .= " AND DATE(pk.tanggal_pengajuan) >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= " AND DATE(pk.tanggal_pengajuan) <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        if (!empty($filters['status'])) {
            $query .= " AND pk.status_pengajuan = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['jenis_kredit'])) {
            $query .= " AND pk.id_jenis_kredit = :jenis_kredit";
            $params['jenis_kredit'] = $filters['jenis_kredit'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (pk.no_pengajuan LIKE :search OR u.nama_lengkap LIKE :search2)";
            $params['search'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
        }

        $query .= " ORDER BY pk.tanggal_pengajuan DESC";

        $stmt = $this->query($query, $params);
        return $stmt->fetchAll();
    }

    /**
     * Get laporan data petugas bank
     */
    public function getLaporanPetugas($filters = [])
    {
        $query = "SELECT 
            u.id_user,
            u.nama_lengkap,
            u.email,
            u.no_hp as no_telepon,
            u.role,
            u.created_at,
            COUNT(DISTINCT v.id_verifikasi) as total_verifikasi,
            COUNT(DISTINCT s.id_survei) as total_survei,
            COUNT(DISTINCT ak.id_analisis) as total_analisis,
            COUNT(DISTINCT p.id_persetujuan) as total_persetujuan
        FROM tb_users u
        LEFT JOIN tb_verifikasi v ON u.id_user = v.id_petugas
        LEFT JOIN tb_survei s ON u.id_user = s.id_petugas
        LEFT JOIN tb_analisis_kredit ak ON u.id_user = ak.id_analis
        LEFT JOIN tb_persetujuan p ON u.id_user = p.id_pimpinan
        WHERE u.role IN ('petugas', 'analis', 'pimpinan')";

        $params = [];

        if (!empty($filters['role'])) {
            $query .= " AND u.role = :role";
            $params['role'] = $filters['role'];
        }

        if (!empty($filters['start_date'])) {
            $query .= " AND DATE(u.created_at) >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= " AND DATE(u.created_at) <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (u.nama_lengkap LIKE :search OR u.email LIKE :search2)";
            $params['search'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
        }

        $query .= " GROUP BY u.id_user, u.nama_lengkap, u.email, u.no_hp, u.role, u.created_at";
        $query .= " ORDER BY u.created_at DESC";

        $stmt = $this->query($query, $params);
        return $stmt->fetchAll();
    }

    /**
     * Get laporan status pengajuan (disetujui/ditolak)
     */
    public function getLaporanStatusPengajuan($filters = [])
    {
        $query = "SELECT 
            pk.id_pengajuan,
            pk.no_pengajuan,
            u.nama_lengkap as nama_nasabah,
            jk.nama_kredit as nama_produk,
            pk.jumlah_pinjaman,
            pk.tenor,
            pk.tanggal_pengajuan,
            ps.keputusan,
            ps.tanggal_keputusan,
            ps.alasan_keputusan,
            pim.nama_lengkap as nama_pimpinan,
            ak.skor_total,
            ak.kesimpulan
        FROM tb_pengajuan_kredit pk
        INNER JOIN tb_nasabah n ON pk.id_nasabah = n.id_nasabah
        INNER JOIN tb_users u ON n.id_user = u.id_user
        INNER JOIN tb_jenis_kredit jk ON pk.id_jenis_kredit = jk.id_jenis_kredit
        INNER JOIN tb_persetujuan ps ON pk.id_pengajuan = ps.id_pengajuan
        LEFT JOIN tb_users pim ON ps.id_pimpinan = pim.id_user
        LEFT JOIN tb_analisis_kredit ak ON pk.id_pengajuan = ak.id_pengajuan
        WHERE ps.keputusan IS NOT NULL";

        $params = [];

        if (!empty($filters['keputusan'])) {
            $query .= " AND ps.keputusan = :keputusan";
            $params['keputusan'] = $filters['keputusan'];
        }

        if (!empty($filters['start_date'])) {
            $query .= " AND DATE(ps.tanggal_keputusan) >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= " AND DATE(ps.tanggal_keputusan) <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        if (!empty($filters['jenis_kredit'])) {
            $query .= " AND pk.id_jenis_kredit = :jenis_kredit";
            $params['jenis_kredit'] = $filters['jenis_kredit'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (pk.no_pengajuan LIKE :search OR u.nama_lengkap LIKE :search2)";
            $params['search'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
        }

        $query .= " ORDER BY ps.tanggal_keputusan DESC";

        $stmt = $this->query($query, $params);
        return $stmt->fetchAll();
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        $stmt1 = $this->query("SELECT COUNT(*) as count FROM tb_users WHERE role = 'nasabah'");
        $r1 = $stmt1->fetch();

        $stmt2 = $this->query("SELECT COUNT(*) as count FROM tb_pengajuan_kredit WHERE status_pengajuan != 'draft'");
        $r2 = $stmt2->fetch();

        $stmt3 = $this->query("SELECT COUNT(*) as count FROM tb_users WHERE role IN ('petugas', 'analis', 'pimpinan')");
        $r3 = $stmt3->fetch();

        $stmt4 = $this->query("SELECT COUNT(*) as count FROM tb_persetujuan WHERE keputusan = 'disetujui'");
        $r4 = $stmt4->fetch();

        $stmt5 = $this->query("SELECT COUNT(*) as count FROM tb_persetujuan WHERE keputusan = 'ditolak'");
        $r5 = $stmt5->fetch();

        $stmt6 = $this->query("SELECT COUNT(*) as count FROM tb_pengajuan_kredit WHERE status_pengajuan NOT IN ('disetujui', 'ditolak', 'draft')");
        $r6 = $stmt6->fetch();

        return [
            'total_nasabah' => $r1['count'] ?? 0,
            'total_pengajuan' => $r2['count'] ?? 0,
            'total_petugas' => $r3['count'] ?? 0,
            'pengajuan_disetujui' => $r4['count'] ?? 0,
            'pengajuan_ditolak' => $r5['count'] ?? 0,
            'pengajuan_pending' => $r6['count'] ?? 0
        ];
    }
}
