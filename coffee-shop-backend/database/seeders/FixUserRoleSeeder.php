<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FixUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Memastikan user yang bukan admin memiliki role 'user'
     */
    public function run(): void
    {
        // Get all users that are not admin
        $users = User::where('role', '!=', 'admin')
                     ->orWhereNull('role')
                     ->get();

        foreach ($users as $user) {
            // Skip admin email
            if ($user->email !== 'admin@thetazine.com') {
                $user->update(['role' => 'user']);
                echo "Fixed user: {$user->email} -> role: user\n";
            }
        }

        // Ensure admin has admin role
        $admin = User::where('email', 'admin@thetazine.com')->first();
        if ($admin && $admin->role !== 'admin') {
            $admin->update(['role' => 'admin']);
            echo "Fixed admin: {$admin->email} -> role: admin\n";
        }

        echo "User roles fixed successfully!\n";
    }
}
