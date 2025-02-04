<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeachersSeeder extends Seeder
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
            'name'=>'faouzia',
            'username'=>'faouzia',
            'address'=>'Barzeh',
            'role'=>2,
            'phone_num'=>'12345',
            'password'=>'12345',
            'created_at'=>now()
        ]);
        DB::table('teachers')->insert([
            'user_id'=>10,
            'created_at'=>now()
        ]);
        DB::table('users')->insert([
            'name'=>'sami',
            'username'=>'sami',
            'address'=>'jouber',
            'role'=>2,
            'phone_num'=>'12345',
            'password'=>'12345',
            'created_at'=>now()
        ]);
        DB::table('teachers')->insert([
            'user_id'=>11,
            'created_at'=>now()
        ]);
        DB::table('users')->insert([
            'name'=>'mohsen',
            'username'=>'mohsen',
            'address'=>'jableh',
            'role'=>2,
            'phone_num'=>'12345',
            'password'=>'12345',
            'created_at'=>now()
        ]);
        DB::table('teachers')->insert([
            'user_id'=>12,
            'created_at'=>now()
        ]);
        DB::table('users')->insert([
            'name'=>'salwa',
            'username'=>'salwa',
            'address'=>'jaramana',
            'role'=>2,
            'phone_num'=>'12345',
            'password'=>'12345',
            'created_at'=>now()
        ]);
        DB::table('teachers')->insert([
            'user_id'=>13,
            'created_at'=>now()
        ]);
    }
}
