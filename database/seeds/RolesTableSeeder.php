<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'admin',
            'display_name' => 'Administrator'
        ]);
        DB::table('roles')->insert([
            'name' => 'customer',
            'display_name' => 'Passholder'
        ]);
        DB::table('roles')->insert([
            'name' => 'vendor',
            'display_name' => 'Venue'
        ]);
    }
}
