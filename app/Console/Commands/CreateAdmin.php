<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class CreateAdmin extends Command
{
    // This is the command you type in the terminal
    protected $signature = 'make:admin';

    // use 'php artisan' to terminal, and find command on make:
    protected $description = 'Creates a new administrator via the terminal';

    public function handle()
    {
        // set admin email
        $email = $this->ask('Email for the admin?');

        // Check if user already exists
        if (Admin::where('email', $email)->exists()) {
            $this->error('A user with this email already exists!');
            return;
        }

        // this->secret method will not show while typing
        $password = $this->secret('Password for the admin?');

        // Confirmation check (optional but helpful)
        if (empty($password)) {
            $this->error('Password cannot be empty!');
            return;
        }

        // add news admin
        Admin::create([
            'first_name' => 'admin', // depende unsa first name
            'last_name' => 'admin', // depende unsa last name
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin', // Ensure 'role' is in your User model's $fillable array!
        ]);

        $this->info("Admin account ($email) created successfully!");
    }
}