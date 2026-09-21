<?php

namespace App\Support;

/**
 * Kumpulan aturan tetap (status, peran, kategori) dalam bentuk array biasa.
 * Ditaruh di satu tempat supaya tidak perlu menulis ulang di setiap controller.
 */
class Workflow
{
    // Daftar status permohonan, urut dari awal sampai selesai.
    public static $statuses = ['Diajukan', 'Diproses', 'Menunggu persetujuan', 'Disetujui', 'Ditolak'];

    // Nama peran yang ditampilkan di layar.
    public static $roles = [
        'admin'   => 'Administrator',
        'petugas' => 'Petugas',
        'siswa'   => 'Siswa',
    ];

    // Jenis permohonan.
    public static $categories = [
        'cuti'    => 'Cuti / izin',
        'dokumen' => 'Dokumen',
        'lainnya' => 'Lainnya',
    ];

    // Warna chip status (nama kelas CSS di app.css).
    public static function statusClass($status)
    {
        if ($status === 'Diajukan') return 'aju';
        if ($status === 'Diproses') return 'proses';
        if ($status === 'Menunggu persetujuan') return 'tunggu';
        if ($status === 'Disetujui') return 'setuju';
        if ($status === 'Ditolak') return 'tolak';
        return 'plain';
    }

    // Status berikutnya yang boleh dipilih tiap peran.
    public static function nextStatuses($role, $currentStatus)
    {
        if ($role === 'petugas' && $currentStatus === 'Diajukan') {
            return ['Diproses'];
        }
        if ($role === 'petugas' && $currentStatus === 'Diproses') {
            return ['Menunggu persetujuan'];
        }
        if ($role === 'admin' && $currentStatus === 'Menunggu persetujuan') {
            return ['Disetujui', 'Ditolak'];
        }
        return [];
    }

    // Status yang menjadi "tugas" tiap peran di daftar permohonan.
    public static function todoStatuses($role)
    {
        if ($role === 'petugas') return ['Diajukan', 'Diproses'];
        if ($role === 'admin') return ['Menunggu persetujuan'];
        return [];
    }

    public static function actionLabel($status)
    {
        if ($status === 'Diproses') return 'Mulai proses';
        if ($status === 'Menunggu persetujuan') return 'Teruskan ke administrator';
        if ($status === 'Disetujui') return 'Setujui';
        if ($status === 'Ditolak') return 'Tolak';
        return $status;
    }

    public static function doneWord($status)
    {
        if ($status === 'Diproses') return 'diproses';
        if ($status === 'Menunggu persetujuan') return 'diteruskan ke administrator';
        if ($status === 'Disetujui') return 'disetujui';
        if ($status === 'Ditolak') return 'ditolak';
        return 'diperbarui';
    }

    // Penjelasan "langkah berikutnya" untuk siswa/petugas/admin.
    public static function info($status, $role)
    {
        $text = [
            'Diajukan' => [
                'siswa'   => 'Petugas akan memeriksa permohonan Anda.',
                'petugas' => 'Periksa kelengkapan permohonan, lalu mulai proses.',
                'admin'   => 'Menunggu petugas memulai proses.',
            ],
            'Diproses' => [
                'siswa'   => 'Petugas sedang memproses permohonan Anda.',
                'petugas' => 'Setelah data diverifikasi, teruskan ke administrator.',
                'admin'   => 'Sedang diproses petugas.',
            ],
            'Menunggu persetujuan' => [
                'siswa'   => 'Permohonan Anda menunggu keputusan administrator.',
                'petugas' => 'Menunggu keputusan administrator.',
                'admin'   => 'Tinjau permohonan ini, lalu setujui atau tolak.',
            ],
            'Disetujui' => ['siswa' => 'Permohonan disetujui.', 'petugas' => 'Permohonan disetujui.', 'admin' => 'Permohonan disetujui.'],
            'Ditolak'   => ['siswa' => 'Permohonan ditolak.', 'petugas' => 'Permohonan ditolak.', 'admin' => 'Permohonan ditolak.'],
        ];

        return $text[$status][$role] ?? '';
    }

    // Label field profil siswa, dipakai untuk hitung kelengkapan.
    public static $profileFields = [
        'nisn'      => 'NISN',
        'kelas'     => 'Kelas',
        'tgl_lahir' => 'Tanggal lahir',
        'telp'      => 'Nomor HP',
        'alamat'    => 'Alamat',
        'wali'      => 'Nama wali',
        'telp_wali' => 'Nomor HP wali',
    ];

    // Persentase kelengkapan profil (0-100).
    public static function completeness($profile)
    {
        if (!$profile) return 0;

        $total = count(self::$profileFields);
        $terisi = 0;

        foreach (array_keys(self::$profileFields) as $field) {
            if (!empty($profile->$field)) {
                $terisi++;
            }
        }

        return (int) round($terisi / $total * 100);
    }

    // Nama field yang masih kosong.
    public static function missing($profile)
    {
        $kosong = [];
        foreach (self::$profileFields as $field => $label) {
            if (!$profile || empty($profile->$field)) {
                $kosong[] = $label;
            }
        }
        return $kosong;
    }
}
