<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'gurugroup@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'branch_id' => null,
            ]
        );

        $branches = [
            ['name' => 'Chennai', 'location' => 'Chennai, Tamil Nadu', 'manager' => 'Manoj', 'phone' => '9000000001', 'email' => 'chennaigurugroup@gmail.com'],
            ['name' => 'Bangalore', 'location' => 'Bangalore, Karnataka', 'manager' => 'Ravi', 'phone' => '9000000002', 'email' => 'bangloregurugroup@gmail.com'],
            ['name' => 'Hyderabad', 'location' => 'Hyderabad, Telangana', 'manager' => 'Suresh', 'phone' => '9000000003', 'email' => 'hydrabadgurugroup@gmail.com'],
            ['name' => 'Coimbatore', 'location' => 'Coimbatore, Tamil Nadu', 'manager' => 'Karthik', 'phone' => '9000000004', 'email' => 'coimbatoregurugroup@gmail.com'],
        ];

        foreach ($branches as $data) {
            $branch = Branch::updateOrCreate(
                ['name' => $data['name']],
                [
                    'location' => $data['location'],
                    'manager' => $data['manager'],
                    'phone' => $data['phone'],
                ]
            );

            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'] . ' Admin',
                    'password' => bcrypt('12345678'),
                    'role' => 'admin',
                    'branch_id' => $branch->id,
                ]
            );
        }
    }
}
