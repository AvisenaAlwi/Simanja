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
            'email' => 'admin@bps.go.id',
            'jabatan' => 'Pimpinan',
            'pendidikan' => 'S2',
            'ti' => 'Tinggi',
            'menulis' => 'Sangat Tinggi',
            'administrasi' => 'Sangat Tinggi',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '196802281989031004',
            'role_id' => 2,
            'pejabat_penilai_nip' => '196802281989031003',
            'name' => 'Supervisor',
            'email' => 'supervisor@bps.go.id',
            'jabatan' => 'Kepala Seksi',
            'pendidikan' => 'S1',
            'ti' => 'Cukup',
            'menulis' => 'Sangat Tinggi',
            'administrasi' => 'Tinggi',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '196802281989031005',
            'role_id' => 3,
            'pejabat_penilai_nip' => '196802281989031004',
            'name' => 'Pegawai',
            'email' => 'pegawai@bps.go.id',
            'jabatan' => 'Pegawai',
            'pendidikan' => 'SMA',
            'ti' => 'Cukup',
            'menulis' => 'Sangat Tinggi',
            'administrasi' => 'Tinggi',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);

        User::create([
            'nip' => '19631004 199102 1 001',
            'role_id' => 3,
            'name' => 'Drs. Sunaryo, M.Si',
            'email' => 'naryo@bps.go.id',
            'jabatan' => 'Kepala',
            'pendidikan' => 'S2',
            'ti' => 'Kurang',
            'menulis' => 'Tinggi',
            'administrasi' => 'Sangat Tinggi',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19651005 198803 1 002',
            'role_id' => 3,
            'name' => 'Rony Mugiartono',
            'email' => 'rmugi@bps.go.id',
            'jabatan' => 'Kasubbag Tata Usaha',
            'pendidikan' => 'S1',
            'ti' => 'Tinggi',
            'menulis' => 'Tinggi',
            'administrasi' => 'Sangat Tinggi',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19750930 199901 1 001',
            'role_id' => 3,
            'name' => 'Henry Soeryaning Handoko, SST',
            'email' => 'henrys@bps.go.id',
            'jabatan' => 'Kasi Statistik Sosial',
            'pendidikan' => 'D-IV',
            'ti' => 'Cukup',
            'menulis' => 'Tinggi',
            'administrasi' => 'Cukup',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19620806 198310 1 001',
            'role_id' => 3,
            'name' => 'Hery Suyanto, SE',
            'email' => 'hery.suyanto@bps.go.id',
            'jabatan' => 'Kasi Statistik Produksi',
            'pendidikan' => 'S1',
            'ti' => 'Sangat Kurang',
            'menulis' => 'Tinggi',
            'administrasi' => 'Cukup',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19681028 199401 2 001',
            'role_id' => 3,
            'name' => 'Ir. Dwi Handayani Prasetyawati, MAP',
            'email' => 'dwi.handayani@bps.go.id',
            'jabatan' => 'Kasi Statistik Distribusi',
            'pendidikan' => 'S2',
            'ti' => 'Kurang',
            'menulis' => 'Tinggi',
            'administrasi' => 'Sangat Tinggi',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19670109 199203 2 001',
            'role_id' => 3,
            'name' => 'Ir. Ernawaty, MM',
            'email' => 'ernawaty@bps.go.id',
            'jabatan' => 'Kasi Nerwilis',
            'pendidikan' => 'S2',
            'ti' => 'Kurang',
            'menulis' => 'Tinggi',
            'administrasi' => 'Sangat Tinggi',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19631204 199003 1 001',
            'role_id' => 3,
            'name' => 'Heru Prasetyo, SE',
            'email' => 'heruprasetyo@bps.go.id',
            'jabatan' => 'Kasi IPDS',
            'pendidikan' => 'S1',
            'ti' => 'Tinggi',
            'menulis' => 'Tinggi',
            'administrasi' => 'Cukup',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19660410 199401 2 001',
            'role_id' => 3,
            'name' => 'Ir. Rahmi Veronika',
            'email' => 'rahmi.veronika@bps.go.id',
            'jabatan' => 'Bendahara Pengeluaran',
            'pendidikan' => 'S1',
            'ti' => 'Sangat Kurang',
            'menulis' => 'Cukup',
            'administrasi' => 'Sangat Tinggi',
            'pengalaman_survei' => 'Cukup',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19770404 200604 1 016',
            'role_id' => 3,
            'name' => 'Rachmad Widi Wijayanto',
            'email' => 'rachmadwidi@bps.go.id',
            'jabatan' => 'Bendahara Penerimaan',
            'pendidikan' => 'SMA',
            'ti' => 'Sangat Kurang',
            'menulis' => 'Cukup',
            'administrasi' => 'Cukup',
            'pengalaman_survei' => 'Cukup',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19851020 201101 2 017',
            'role_id' => 3,
            'name' => 'Eka Prahara Resbiyanti, A.Md',
            'email' => 'eka.prahara@bps.go.id',
            'jabatan' => 'Pengelola Barang Persediaan dan BMN',
            'pendidikan' => 'D-III',
            'ti' => 'Tinggi',
            'menulis' => 'Cukup',
            'administrasi' => 'Tinggi',
            'pengalaman_survei' => 'Cukup',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19881028 201101 1 004',
            'role_id' => 3,
            'name' => 'Satria Candra Wibawa, A.Md',
            'email' => 'satria.wibawa@bps.go.id',
            'jabatan' => 'Statistisi Pelaksana Lanjutan',
            'pendidikan' => 'D-III',
            'ti' => 'Sangat Tinggi',
            'menulis' => 'Cukup',
            'administrasi' => 'Kurang',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19840412 200502 2 001',
            'role_id' => 3,
            'name' => 'Rhyke Chrisdiana Novita, SE',
            'email' => 'rhyke.novita@bps.go.id',
            'jabatan' => 'Statistisi Pertama',
            'pendidikan' => 'S1',
            'ti' => 'Kurang',
            'menulis' => 'Cukup',
            'administrasi' => 'Tinggi',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19851002 200902 2 012',
            'role_id' => 3,
            'name' => 'Ratri Adhipradani Ratih, S.Si',
            'email' => 'ratri@bps.go.id',
            'jabatan' => 'Statistisi Muda',
            'pendidikan' => 'S1',
            'ti' => 'Kurang',
            'menulis' => 'Tinggi',
            'administrasi' => 'Kurang',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19880330 201012 2 002',
            'role_id' => 3,
            'name' => 'Saras Wati Utami, S.Si',
            'email' => 'saras.wati@bps.go.id',
            'jabatan' => 'Statistisi Pertama',
            'pendidikan' => 'S1',
            'ti' => 'Kurang',
            'menulis' => 'Cukup',
            'administrasi' => 'Kurang',
            'pengalaman_survei' => 'Cukup',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19700925 199401 2 001',
            'role_id' => 3,
            'name' => 'Soekesi Irawati, S.Psi, MM',
            'email' => 'soekesi.irawati@bps.go.id',
            'jabatan' => 'Statistisi Muda',
            'pendidikan' => 'S2',
            'ti' => 'Sangat Kurang',
            'menulis' => 'Tinggi',
            'administrasi' => 'Cukup',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19660424 199203 2 002',
            'role_id' => 3,
            'name' => 'Ir. Lies Alfiah',
            'email' => 'lies.alfiah@bps.go.id',
            'jabatan' => 'Statistisi Muda',
            'pendidikan' => 'S1',
            'ti' => 'Cukup',
            'menulis' => 'Tinggi',
            'administrasi' => 'Tinggi',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19830910 200602 2 001',
            'role_id' => 3,
            'name' => 'Tasmilah, SST',
            'email' => 'tasmilah@bps.go.id',
            'jabatan' => 'Statistisi Muda',
            'pendidikan' => 'D4',
            'ti' => 'Cukup',
            'menulis' => 'Sangat Tinggi',
            'administrasi' => 'Cukup',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19680228 198903 1 003',
            'role_id' => 3,
            'name' => 'Saruni Gincahyo, SE',
            'email' => 'saruni.gincahyo@bps.go.id',
            'jabatan' => 'Statistisi Penyelia/KSK Kedungkandang',
            'pendidikan' => 'S1',
            'ti' => 'Sangat Kurang',
            'menulis' => 'Cukup',
            'administrasi' => 'Kurang',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19701225 199703 1 004',
            'role_id' => 3,
            'name' => 'Yusuf Fatoni, SE',
            'email' => 'yusuf.fatoni@bps.go.id',
            'jabatan' => 'Statistisi Pelaksana Lanjutan/KSK Sukun',
            'pendidikan' => 'S1',
            'ti' => 'Sangat Kurang',
            'menulis' => 'Cukup',
            'administrasi' => 'Kurang',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19680820 198903 1 003',
            'role_id' => 3,
            'name' => 'Agustono Rahadi',
            'email' => 'agustono.rahadi@bps.go.id',
            'jabatan' => 'Statistisi Penyelia/KSK Klojen',
            'pendidikan' => 'SMA',
            'ti' => 'Sangat Kurang',
            'menulis' => 'Cukup',
            'administrasi' => 'Kurang',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19620917 198503 1 005',
            'role_id' => 3,
            'name' => 'Wahyu Hery Supriyanto, SE',
            'email' => 'wahyuheri@bps.go.id',
            'jabatan' => 'Statistisi Penyelia/KSK Blimbing',
            'pendidikan' => 'S1',
            'ti' => 'Sangat Kurang',
            'menulis' => 'Cukup',
            'administrasi' => 'Kurang',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '19880229 201101 1 005',
            'role_id' => 3,
            'name' => 'Rendra Anandhika, A.Md',
            'email' => 'rendra@bps.go.id',
            'jabatan' => 'Statistisi Pelaksana/KSK Lowokwaru',
            'pendidikan' => 'D3',
            'ti' => 'Tinggi',
            'menulis' => 'Cukup',
            'administrasi' => 'Kurang',
            'pengalaman_survei' => 'Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
        User::create([
            'nip' => '165150207111067',
            'role_id' => 1,
            'name' => 'Muhammad Fajriansyah',
            'email' => 'rian@bps.go.id',
            'jabatan' => 'Pegawai PKL Magang',
            'pendidikan' => 'SMA',
            'ti' => 'Sangat Tinggi',
            'menulis' => 'Sangat Tinggi',
            'administrasi' => 'Sangat Tinggi',
            'pengalaman_survei' => 'Sangat Tinggi',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia'),
        ]);
    }
}
