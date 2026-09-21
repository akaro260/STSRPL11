<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Notif;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Buat pengguna contoh ---
        $admin = User::create(['name' => 'Bu Ratna Wijaya', 'email' => 'admin@sekolah.id', 'role' => 'admin', 'password' => Hash::make('Admin@123')]);
        $p1 = User::create(['name' => 'Pak Dedi Kurniawan', 'email' => 'petugas@sekolah.id', 'role' => 'petugas', 'password' => Hash::make('Petugas@123')]);
        $p2 = User::create(['name' => 'Bu Lestari Putri', 'email' => 'lestari@sekolah.id', 'role' => 'petugas', 'password' => Hash::make('Petugas@123')]);
        $s1 = User::create(['name' => 'Aisyah Putri Rahma', 'email' => 'siswa@sekolah.id', 'role' => 'siswa', 'password' => Hash::make('Siswa@123')]);
        $s2 = User::create(['name' => 'Bagas Prasetyo', 'email' => 'bagas@sekolah.id', 'role' => 'siswa', 'password' => Hash::make('Siswa@123')]);
        $s3 = User::create(['name' => 'Citra Dewi Anggraini', 'email' => 'citra@sekolah.id', 'role' => 'siswa', 'password' => Hash::make('Siswa@123')]);
        $s4 = User::create(['name' => 'Dimas Arya Nugroho', 'email' => 'dimas@sekolah.id', 'role' => 'siswa', 'password' => Hash::make('Siswa@123')]);

        foreach ([$admin, $p1, $p2, $s1, $s2, $s3, $s4] as $u) {
            AuditLog::create(['user_name' => $admin->name, 'role' => 'admin', 'action' => 'user.create', 'target' => $u->email, 'detail' => 'Peran: ' . $u->role]);
        }

        // --- Profil siswa ---
        $s1->profile()->create(['nisn' => '0081234567', 'kelas' => 'XI IPA 1', 'tgl_lahir' => '2009-03-14', 'telp' => '081234567890', 'alamat' => 'Jl. Kemang Raya No. 12, Jakarta Selatan', 'wali' => 'Ahmad Rahma', 'telp_wali' => '081298765432']);
        $s2->profile()->create(['nisn' => '0082345678', 'kelas' => 'XI IPS 2', 'tgl_lahir' => '2009-07-02', 'telp' => '082111223344', 'alamat' => 'Jl. Pemuda No. 45, Jakarta Timur', 'wali' => 'Sri Prasetyo', 'telp_wali' => '081355667788']);
        $s3->profile()->create(['nisn' => '0083456789', 'kelas' => 'X IPA 3', 'tgl_lahir' => '2010-01-25', 'telp' => '085712345678', 'alamat' => 'Jl. Melati No. 8, Depok', 'wali' => 'Wahyu Anggraini', 'telp_wali' => '081234000111']);
        $s4->profile()->create(['nisn' => '0084567890', 'kelas' => 'XII IPS 1', 'tgl_lahir' => '2008-11-09', 'telp' => '087855551234']);

        // --- Contoh permohonan ---
        $this->buatPermohonan(1, $s1, 'cuti', 'Izin mengikuti olimpiade sains', 'Mohon izin tidak masuk pada 15-16 September 2026 untuk mengikuti Olimpiade Sains tingkat kota.', '2026-09-15', '2026-09-16', [
            ['Diproses', $p1, 'Surat tugas sudah diterima.'],
            ['Menunggu persetujuan', $p1, 'Data lengkap, mohon persetujuan.'],
            ['Disetujui', $admin, 'Disetujui. Semoga sukses.'],
        ]);

        $this->buatPermohonan(2, $s4, 'dokumen', 'Surat rekomendasi beasiswa', 'Saya membutuhkan surat rekomendasi sekolah untuk pendaftaran beasiswa prestasi.', null, null, [
            ['Diproses', $p2, 'Berkas prestasi sedang diverifikasi.'],
            ['Menunggu persetujuan', $p2, 'Verifikasi selesai.'],
            ['Disetujui', $admin, null],
        ]);

        $this->buatPermohonan(3, $s2, 'cuti', 'Izin sakit dua hari', 'Saya sakit dan disarankan dokter untuk beristirahat selama dua hari.', '2026-09-16', '2026-09-17', [
            ['Diproses', $p1, 'Surat dokter diterima.'],
            ['Menunggu persetujuan', $p1, null],
            ['Disetujui', $admin, null],
        ]);

        $this->buatPermohonan(4, $s2, 'dokumen', 'Kartu pelajar pengganti', 'Kartu pelajar saya hilang. Mohon dicetakkan kartu pengganti.', null, null, [
            ['Diproses', $p2, null],
            ['Menunggu persetujuan', $p2, 'Mohon dicek kelengkapannya.'],
            ['Ditolak', $admin, 'Mohon lampirkan surat keterangan kehilangan bertanda tangan orang tua.'],
        ]);

        $this->buatPermohonan(5, $s3, 'dokumen', 'Legalisir rapor semester 1', 'Mohon dilegalisir 3 lembar rapor semester 1 untuk keperluan pendaftaran kursus.', null, null, [
            ['Diproses', $p2, 'Berkas sedang dilegalisir di tata usaha.'],
        ]);

        $this->buatPermohonan(6, $s3, 'cuti', 'Izin keperluan keluarga', 'Ada acara keluarga di luar kota sehingga saya perlu izin dua hari.', '2026-09-22', '2026-09-23', []);

        $this->buatPermohonan(7, $s1, 'dokumen', 'Surat keterangan siswa aktif', 'Surat ini diperlukan untuk pengurusan tunjangan pendidikan orang tua saya.', null, null, [
            ['Diproses', $p1, 'Dokumen sedang disiapkan.'],
            ['Menunggu persetujuan', $p1, 'Data sudah diverifikasi, mohon persetujuan.'],
        ]);

        $this->buatPermohonan(8, $s4, 'lainnya', 'Perubahan data alamat', 'Keluarga saya pindah rumah. Mohon data alamat saya di sistem sekolah diperbarui.', null, null, []);
    }

    /**
     * Bikin satu permohonan lengkap dengan langkah-langkahnya.
     * $steps isinya daftar [status, siapa yang mengubah, catatan].
     */
    protected function buatPermohonan($nomorUrut, $siswa, $kategori, $judul, $keterangan, $dari, $sampai, $steps)
    {
        $no = 'REQ-' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        $permohonan = Permohonan::create([
            'no' => $no,
            'student_id' => $siswa->id,
            'category' => $kategori,
            'title' => $judul,
            'description' => $keterangan,
            'from_date' => $dari,
            'to_date' => $sampai,
            'status' => 'Diajukan',
        ]);

        $permohonan->steps()->create(['actor_name' => $siswa->name, 'role' => 'siswa', 'status' => 'Diajukan', 'note' => 'Permohonan diajukan.']);

        AuditLog::create(['user_name' => $siswa->name, 'role' => 'siswa', 'action' => 'request.create', 'target' => $no, 'detail' => $judul]);

        foreach ($steps as [$status, $actor, $note]) {
            $permohonan->status = $status;
            if (in_array($status, ['Disetujui', 'Ditolak'])) {
                $permohonan->decided_at = now();
            }
            $permohonan->save();

            $permohonan->steps()->create(['actor_name' => $actor->name, 'role' => $actor->role, 'status' => $status, 'note' => $note]);

            AuditLog::create(['user_name' => $actor->name, 'role' => $actor->role, 'action' => 'request.update', 'target' => $no, 'detail' => $status . ($note ? ": {$note}" : '')]);

            Notif::create(['user_id' => $siswa->id, 'permohonan_id' => $permohonan->id, 'text' => "{$no} \"{$judul}\" kini berstatus {$status}"]);
        }

        return $permohonan;
    }
}
