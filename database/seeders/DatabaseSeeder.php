<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\Jenis;
use App\Models\Syarat;
use App\Models\Peserta;
use App\Models\Seleksi;
use App\Models\BankSoal;
use App\Models\Kategori;
use App\Models\RoleUser;
use App\Models\Identitas;
use App\Models\Institusi;
use App\Models\Pendaftar;
use App\Models\Interviewer;
use App\Models\RoleSeleksi;
use App\Models\Verifikator;
use App\Models\SubInstitusi;
use App\Models\TopikInterview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\InstitusiController;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();


        $dtdef = [
            ['nama' => 'Administrator'],
            ['nama' => 'Interviewer'],
            ['nama' => 'Verifikator'],
        ];

        foreach ($dtdef as $dt) {
            Role::create([
                'nama' => $dt['nama'],
            ]);
        }

        $dtdef = [
            ['name' => 'administrator', 'email' => 'admin@app.com'],
            ['name' => 'verifikator 1', 'email' => 'ver1@app.com'],
            ['name' => 'alfath', 'email' => 'fath@app.com'],
            ['name' => 'aleesya', 'email' => 'eca@app.com'],
        ];

        foreach ($dtdef as $dt) {
            User::create([
                'name' => $dt['name'],
                'email' => $dt['email'],
                'password' => Hash::make('00000000'),
            ]);
        }

        //role user
        $dtdef = [
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 1, 'role_id' => 2], //inter id 2
            ['user_id' => 2, 'role_id' => 2], //inter id 3
            ['user_id' => 1, 'role_id' => 3], //verif id 4
            ['user_id' => 2, 'role_id' => 3], //verif id 5
            ['user_id' => 4, 'role_id' => 3], //verif id 6
        ];

        foreach ($dtdef as $dt) {
            RoleUser::create([
                'user_id' => $dt['user_id'],
                'role_id' => $dt['role_id'],
            ]);
        }

        Institusi::create([
            'nama' => 'IAIN Kendari'
        ]);

        $dtdef = [
            ['institusi_id' => 1, 'nama' => 'Teknik Informatika', 'jenis' => 's1'],
            ['institusi_id' => 1, 'nama' => 'Teknik Sipil', 'jenis' => 's1'],
            ['institusi_id' => 1, 'nama' => 'Teknik Mesin', 'jenis' => 's1'],
            ['institusi_id' => 1, 'nama' => 'Teknik Gelologi', 'jenis' => 's1'],
            ['institusi_id' => 1, 'nama' => 'Farmasi', 'jenis' => 's1'],
            ['institusi_id' => 1, 'nama' => 'Kedokteran', 'jenis' => 's1'],
        ];

        foreach ($dtdef as $dt) {
            SubInstitusi::create([
                'institusi_id' => $dt['institusi_id'],
                'nama' => $dt['nama'],
                'jenis' => $dt['jenis'],
            ]);
        }

        $dtdef = [
            ['user_id' => 1, 'noid' => '130301001', 'sub_institusi_id' => 1, 'is_aktif' => 1],
            ['user_id' => 3, 'noid' => '19010101018', 'sub_institusi_id' => 1, 'is_aktif' => 1],
            ['user_id' => 4, 'noid' => '2023010101003', 'sub_institusi_id' => 1, 'is_aktif' => 1],
        ];

        foreach ($dtdef as $dt) {
            Peserta::create([
                'user_id' => $dt['user_id'],
                'noid' => $dt['noid'],
                'sub_institusi_id' => $dt['sub_institusi_id'],
                'is_aktif' => $dt['is_aktif'],
            ]);
        }

        //peserta tambahan
        for ($i = 5; $i <= 20; $i++) {
            User::create([
                'name' => "User " . $i,
                'email' => "user" . $i . "@mail.com",
                'password' => Hash::make('00000000'),
            ]);
        }
        for ($i = 5; $i <= 20; $i++) {
            Peserta::create([
                'user_id' => $i,
                'noid' => "2023010101" . (1000 + $i),
                'sub_institusi_id' => rand(1, 6),
                'is_aktif' => 1,
            ]);
        }


        $dtdef = [
            ['nama' => 'Bidik Misi/KIP'],
            ['nama' => 'BI'],
            ['nama' => 'Prestasi'],
            ['nama' => 'Pemerintah Daerah'],
            ['nama' => 'Eksternal'],
            ['nama' => 'Hafidz Al-Quran'],
            ['nama' => 'Lainnya'],
        ];

        foreach ($dtdef as $dt) {
            Jenis::create([
                'nama' => $dt['nama'],
            ]);
        }


        $dtdef = [
            ['nama' => 'Seleksi Beasiswa BI', 'tahun' => date('Y'), 'jenis_id' => 2, 'is_publish' => 1],
            ['nama' => 'Rekruitmen Baru Penerima KIP', 'tahun' => date('Y'), 'jenis_id' => 1, 'is_publish' => 1],
        ];

        foreach ($dtdef as $dt) {
            Seleksi::create([
                'nama' => $dt['nama'],
                'tahun' => $dt['tahun'],
                'is_publish' => $dt['is_publish'],
                'jenis_id' => $dt['jenis_id'],
                'daftar_mulai' => date('y-m-d'),
                'daftar_selesai' => date('y-m-d'),
                'verifikasi_mulai' => date('y-m-d'),
                'verifikasi_selesai' => date('y-m-d'),
                'wawancara_mulai' => date('y-m-d'),
                'wawancara_selesai' => date('y-m-d'),
            ]);
        }

        $dtdef = [
            ['nama' => 'Pas Foto', 'jenis' => 'img', 'is_wajib' => 1, 'seleksi_id' => 1],
            ['nama' => 'KTP', 'jenis' => 'pdf', 'is_wajib' => 1, 'seleksi_id' => 1],
            ['nama' => 'Kartu Keluarga', 'jenis' => 'pdf', 'is_wajib' => 1, 'seleksi_id' => 1],
            ['nama' => 'KHS Terakhir', 'jenis' => 'pdf', 'is_wajib' => 1, 'seleksi_id' => 1],
            ['nama' => 'Kartu KIP', 'jenis' => 'img', 'is_wajib' => 0, 'seleksi_id' => 2],
            ['nama' => 'Surat Keterangan Tidak Mampu', 'jenis' => 'pdf', 'is_wajib' => 0, 'seleksi_id' => 2],
            ['nama' => 'Pas Foto', 'jenis' => 'img', 'is_wajib' => 1, 'seleksi_id' => 2],
            ['nama' => 'KTP', 'jenis' => 'pdf', 'is_wajib' => 1, 'seleksi_id' => 2],
            ['nama' => 'Kartu Keluarga', 'jenis' => 'pdf', 'is_wajib' => 1, 'seleksi_id' => 2],
            ['nama' => 'Ijazah SMA', 'jenis' => 'pdf', 'is_wajib' => 1, 'seleksi_id' => 2],
            ['nama' => 'Raport Kelas 10 sd 12', 'jenis' => 'pdf', 'is_wajib' => 1, 'seleksi_id' => 2],
        ];

        foreach ($dtdef as $dt) {
            Syarat::create([
                'nama' => $dt['nama'],
                'jenis' => $dt['jenis'],
                'is_wajib' => $dt['is_wajib'],
                'seleksi_id' => $dt['seleksi_id'],
            ]);
        }

        //verifikator
        $dtdef = [
            ['role_user_id' => 3, 'seleksi_id' => 1],
            ['role_user_id' => 4, 'seleksi_id' => 1],
            ['role_user_id' => 2, 'seleksi_id' => 2],
            ['role_user_id' => 3, 'seleksi_id' => 2],
            ['role_user_id' => 4, 'seleksi_id' => 2],
            ['role_user_id' => 5, 'seleksi_id' => 2],
            ['role_user_id' => 6, 'seleksi_id' => 2],
        ];

        foreach ($dtdef as $dt) {
            RoleSeleksi::create([
                'role_user_id' => $dt['role_user_id'],
                'seleksi_id' => $dt['seleksi_id'],
            ]);
        }

        $dtdef = [
            ['nama' => 'Seleksi Beasiswa BI', 'tahun' => date('Y'), 'jenis_id' => 2, 'is_publish' => 1],
            ['nama' => 'Rekruitmen Baru Penerima KIP', 'tahun' => date('Y'), 'jenis_id' => 1, 'is_publish' => 1],
        ];

        for ($i = 1; $i <= 11; $i++) {
            Pendaftar::create([
                'tahun' => date('Y'),
                'peserta_id' => $i,
                'seleksi_id' => 2,
            ]);
        }

        for ($i = 12; $i <= 18; $i++) {
            Pendaftar::create([
                'tahun' => date('Y'),
                'peserta_id' => $i,
                'seleksi_id' => 1,
            ]);
        }

        for ($i = 1; $i <= 20; $i++) {
            Identitas::create([
                'user_id' => $i,
                'alamat' => 'BTN Rizky Ranomeeto',
                'jenis_kelamin' => 'L',
                'hp' => '08533101999' . $i,
                'tgl_lahir' => date('Y-m-d'),
            ]);
        }


        $dtdef = [
            ['nama' => 'Wawasan Kebangsaan'],
            ['nama' => 'Tes Potensi Akademik'],
            ['nama' => 'Penalaran Matematikan'],
            ['nama' => 'Wawasan Keagamaan'],
            ['nama' => 'Tes Kompetensi Dasar'],
        ];

        foreach ($dtdef as $dt) {
            Kategori::create([
                'nama' => $dt['nama'],
            ]);
        }

        $dtdef = [
            ['soal' => 'Apa yang Anda pahami tentang wawasan kebangsaan?', 'kategori_id' => 1],
            ['soal' => 'Mengapa wawasan kebangsaan penting bagi persatuan dan kesatuan bangsa?', 'kategori_id' => 1],
            ['soal' => 'Apa yang menurut Anda menjadi akar penyebab munculnya paham radikalisme di Indonesia?', 'kategori_id' => 1],
            ['soal' => 'Bagaimana cara kita membedakan antara perbedaan pendapat yang sehat dengan paham radikalisme?', 'kategori_id' => 1],
            ['soal' => 'Apa peran pemuda dalam menangkal paham radikalisme?', 'kategori_id' => 1],
            ['soal' => 'Apa yang membedakan antara aksi terorisme dengan aksi kekerasan lainnya?', 'kategori_id' => 1],
            ['soal' => 'Bagaimana dampak aksi terorisme terhadap kehidupan bermasyarakat?', 'kategori_id' => 1],
            ['soal' => 'Apa peran pemerintah dalam mencegah dan menanggulangi aksi terorisme?', 'kategori_id' => 1],
            ['soal' => 'Menurut Anda, apa saja upaya yang dapat dilakukan oleh masyarakat untuk mencegah penyebaran paham radikalisme dan terorisme?', 'kategori_id' => 1],
            ['soal' => 'Bagaimana cara kita membangun masyarakat yang toleran dan inklusif untuk mencegah tumbuhnya bibit-bibit radikalisme?', 'kategori_id' => 1],
        ];

        foreach ($dtdef as $dt) {
            BankSoal::create([
                'soal' => $dt['soal'],
                'kategori_id' => $dt['kategori_id'],
            ]);
        }

        $dtdef = [
            ['bank_soal_id' => 1, 'bobot' => 20,  'seleksi_id' => 1],
            ['bank_soal_id' => 2, 'bobot' => 20,  'seleksi_id' => 2],
            ['bank_soal_id' => 3, 'bobot' => 20,  'seleksi_id' => 1],
            ['bank_soal_id' => 4, 'bobot' => 20,  'seleksi_id' => 2],
            ['bank_soal_id' => 5, 'bobot' => 20,  'seleksi_id' => 1],
            ['bank_soal_id' => 6, 'bobot' => 20,  'seleksi_id' => 2],
            ['bank_soal_id' => 7, 'bobot' => 20,  'seleksi_id' => 1],
            ['bank_soal_id' => 8, 'bobot' => 20,  'seleksi_id' => 2],
            ['bank_soal_id' => 9, 'bobot' => 20,  'seleksi_id' => 1],
            ['bank_soal_id' => 10, 'bobot' => 20,  'seleksi_id' => 2],
        ];

        foreach ($dtdef as $dt) {
            TopikInterview::create([
                'bank_soal_id' => $dt['bank_soal_id'],
                'bobot' => $dt['bobot'],
                'seleksi_id' => $dt['seleksi_id'],
            ]);
        }
    }
}
