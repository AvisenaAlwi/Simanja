<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nip' => '196802281989031003',
            'role_id' => 1,
            'pejabat_penilai_nip' => '196802281989031003',
            'name' => 'Admin',
            'email' => 'admin@malangkota.bps.go.id',
            'jabatan' => 'Pimpinan',
            'pendidikan' => 'S1',
            'ti' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '196802281989031004',
            'role_id' => 2,
            'pejabat_penilai_nip' => '196802281989031003',
            'name' => 'Supervisor',
            'email' => 'supervisor@malangkota.bps.go.id',
            'jabatan' => 'Kepala Seksi',
            'pendidikan' => 'S1',
            'ti' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '196802281989031005',
            'role_id' => 3,
            'pejabat_penilai_nip' => '196802281989031004',
            'name' => 'Staf',
            'email' => 'staf@malangkota.bps.go.id',
            'jabatan' => 'Staf',
            'pendidikan' => 'SLTA',
            'ti' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        
    }
}