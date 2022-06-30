<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class parentsSeeder extends Seeder
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
            'name'=>'Mohamad Rish',
            'username'=>'moh_rish',
            'address'=>'ruken aldeen',
            'role'=>3,
            'phone_num'=>'12345',
            'password'=>'12345',
            'created_at'=>now()
        ]);
        DB::table('paarents')->insert([
           'user_id'=>2,
            'created_at'=>now()
        ]);
        DB::table('users')->insert([
            'name'=>'Ahmad Barakat',
            'username'=>'ahmad_barakat',
            'address'=>'mazzeh',
            'role'=>3,
            'phone_num'=>'12345',
            'password'=>'12345',
            'created_at'=>now()
        ]);
        DB::table('paarents')->insert([
            'user_id'=>3,
            'created_at'=>now()
        ]);
        DB::table('users')->insert([
            'name'=>'Khalid Durra',
            'username'=>'khalid_durra',
            'address'=>'qudsia',
            'role'=>3,
            'phone_num'=>'12345',
            'password'=>'12345',
            'created_at'=>now()
        ]);
        DB::table('paarents')->insert([
            'user_id'=>4,
            'created_at'=>now()
        ]);
        DB::table('users')->insert([
            'name'=>'Ghassan Dukmak',
            'username'=>'ghassan_dukmak',
            'address'=>'mashroua dummar',
            'role'=>3,
            'phone_num'=>'12345',
            'password'=>'12345',
            'created_at'=>now()
        ]);
        DB::table('paarents')->insert([
            'user_id'=>5,
            'created_at'=>now()
        ]);
    }
}
