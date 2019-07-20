<?php

use Illuminate\Database\Seeder;
use App\Roles;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Roles::create([
            'name' => 'Admin',
            'slug' => 'admin'
        ]);
        Roles::create([
            'name' => 'Supervisor',
            'slug' => 'supervisor'
        ]);
        Roles::create([
            'name' => 'Pegawai',
            'slug' => 'pegawai'
        ]);
    }
}
