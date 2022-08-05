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
            'tag_name'=>'EVENT',
            'created_at'=>now()
        ]);
        DB::table('responsibilities')->insert([
            'tag_name'=>'TRANSPORTATION',
            'created_at'=>now()
        ]);
        DB::table('responsibilities')->insert([
            'tag_name'=>'COMPLAINS',
            'created_at'=>now()
        ]);
        DB::table('responsibilities')->insert([
            'tag_name'=>'LIBRARY',
            'created_at'=>now()
        ]);
    }
}
