<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsSeeder extends Seeder
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
            'name'=>'Anas Rish',
            'username'=>'anas_rish',
            'address'=>'ruken aldeen',
            'role'=>4,
            'phone_num'=>'12345',
            'password'=>'12345'
        ]);
        DB::table('students')->insert([
            'user_id'=>6,
            'parent_id'=>2,
            'class_id'=>1,
            'classroom_id'=>1,
            'bus_id'=>1

        ]);
        DB::table('users')->insert([
            'name'=>'Hadi Barakat',
            'username'=>'hadi_barakat',
            'address'=>'mazzeh',
            'role'=>4,
            'phone_num'=>'12345',
            'password'=>'12345'
        ]);
        DB::table('students')->insert([
            'user_id'=>7,
            'parent_id'=>3,
            'class_id'=>1,
            'classroom_id'=>1,
            'bus_id'=>1
        ]);
        DB::table('users')->insert([
            'name'=>'Anas Durra',
            'username'=>'anas_durra',
            'address'=>'qudsia',
            'role'=>4,
            'phone_num'=>'12345',
            'password'=>'12345'
        ]);
        DB::table('students')->insert([
            'user_id'=>8,
            'parent_id'=>4,
            'class_id'=>2,
            'classroom_id'=>2,
            'bus_id'=>2
        ]);
        DB::table('users')->insert([
            'name'=>'Juman Dukmak',
            'username'=>'juman_dukmak',
            'address'=>'mashroua dummar',
            'role'=>4,
            'phone_num'=>'12345',
            'password'=>'12345'
        ]);
        DB::table('students')->insert([
            'user_id'=>9,
            'parent_id'=>5,
            'class_id'=>2,
            'classroom_id'=>2,
            'bus_id'=>2
        ]);

    }
}
