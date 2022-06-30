<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArchiveYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('archive_years')->insert([
            'year'=>now()->month<9?now()->year-1:now()->year,
            'active'=> true,
            'created_at'=>now()
        ]);
    }
}
