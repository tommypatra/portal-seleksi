<?php

namespace Database\Seeders;

use App\Http\Controllers\InstitusiController;
use App\Models\Admin;
use App\Models\Interviewer;
use App\Models\Jenis;
use App\Models\Institusi;
use App\Models\Peserta;
use App\Models\SubInstitusi;
use App\Models\User;
use App\Models\verifikator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        //akses untuk user_id 1 admin untuk semua grup
        Admin::create([
            'user_id' => 1,
            'is_aktif' => 1,
        ]);

        Interviewer::create([
            'user_id' => 1,
            'is_aktif' => 1,
        ]);

        Interviewer::create([
            'user_id' => 3,
            'is_aktif' => 1,
        ]);

        verifikator::create([
            'user_id' => 3,
            'is_aktif' => 1,
        ]);

        verifikator::create([
            'user_id' => 1,
            'is_aktif' => 1,
        ]);

        verifikator::create([
            'user_id' => 2,
            'is_aktif' => 1,
        ]);

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

        Peserta::create([
            'user_id' => 3,
            'noid' => '2023010101001',
            'sub_institusi_id' => 1,
            'is_aktif' => 1,
        ]);

        Peserta::create([
            'user_id' => 4,
            'noid' => '2023010101002',
            'sub_institusi_id' => 2,
            'is_aktif' => 1,
        ]);

        Peserta::create([
            'user_id' => 1,
            'noid' => '9090001.1',
            'sub_institusi_id' => 1,
            'is_aktif' => 1,
        ]);

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
    }
}
