<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('buses')->insert([
            'name'=>'G',
            'capacity'=>20,
            'supervisor'=>'Ammar',
            'supervisor_num'=>'12345',
            'supervisor_address'=>'shalaan',
        ]);
        DB::table('buses')->insert([
            'name'=>'H',
            'capacity'=>20,
            'supervisor'=>'Samia',
            'supervisor_num'=>'12345',
            'supervisor_address'=>'shalaan',
        ]);
    }
}
