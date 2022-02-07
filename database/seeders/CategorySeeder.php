<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        DB::table('categories')->insert([
            ['id' => 0, 'name' => 'School'],
            ['id' => 1, 'name' => 'Work'],
            ['id' => 2, 'name' => 'Self-care']
        ]);

    }
}
