<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AfterDropSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->call([
            SubjectsSeeder::class,
            ClassesSeeder::class,
            ClassesSubjectsSeeder::class,
            ClassroomsSeeder::class,
            parentsSeeder::class,
            BusesSeeder::class,
            StudentsSeeder::class,
            TeachersSeeder::class,
            TeachersSubjectsSeeder::class,
            TeachersClassroomsSeeder::class,
            ClassroomsTeachersSubjectsSeeder::class,
            TypesSeeder::class
        ]);
    }
}
