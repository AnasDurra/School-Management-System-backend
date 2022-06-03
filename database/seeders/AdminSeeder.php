<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class adminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'username' => 'admin',
            'password' => 'admin',
            'name'=>'owner',
            'phone_num'=>'0000',
            'address'=>'tbd',
            'role'=>0
        ]);
        DB::table('admins')->insert([
           'user_id'=>1
        ]);
    }
}
