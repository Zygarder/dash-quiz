<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class adminseed extends Seeder
{
    public function run(): void
    {
        #just seeding admin for db
        DB::insert("
    INSERT INTO admin (first_name, last_name, email, password)
    VALUES (?, ?, ?, ?)", [
            'louie',
            'guiritan',
            'guiritan@example.com',
            Hash::make('guiritan')
        ]);
    }
}
