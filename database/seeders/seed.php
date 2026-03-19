<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class seed extends Seeder
{
    public function run(): void
    {
        #just seeding admin for db
        DB::insert("
    INSERT INTO dasher (first_name, last_name, email, password)
    VALUES (?, ?, ?, ?)", [
            'user',
            'name',
            'un@example.com',
            Hash::make('12345678')
        ]);
    }
}
