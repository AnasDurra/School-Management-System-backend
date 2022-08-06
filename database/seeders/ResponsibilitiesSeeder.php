<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsibilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('responsibilities')->insert([
            'responsibility_name'=>'events',
            'created_at'=>now()
        ]);
        DB::table('responsibilities')->insert([
            'responsibility_name'=>'transportation',
            'created_at'=>now()
        ]);
        DB::table('responsibilities')->insert([
            'responsibility_name'=>'complaints',
            'created_at'=>now()
        ]);
        DB::table('responsibilities')->insert([
            'responsibility_name'=>'library',
            'created_at'=>now()
        ]);
        DB::table('responsibilities')->insert([
            'responsibility_name'=>'absents',
            'created_at'=>now()
        ]);

        for($i=1 ; $i<=5 ; $i++)
            DB::table('admin_responsibilities')->insert([
                'admin_id'=>1,
                'responsibility_id'=>$i,
                'created_at'=>now()
            ]);
    }
}
