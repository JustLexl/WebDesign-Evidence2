<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;

class UserSeeder extends Seeder
{
    public function run()
    {
        $adminDept = Department::where('name', 'Admin')->first();
        $salesDept = Department::where('name', 'Sales')->first();
        $purchasingDept = Department::where('name', 'Purchasing')->first();
        $warehouseDept = Department::where('name', 'Warehouse')->first();
        $routeDept = Department::where('name', 'Route')->first();

        User::updateOrCreate(
            ['email' => 'admin@halcon.com'],
            [
                'name' => 'Default Administrator',
                'password' => Hash::make('password123'),
                'department_id' => $adminDept ? $adminDept->id : null,
                'is_active' => true,
                'confirmed' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'sales@halcon.com'],
            [
                'name' => 'Carlos Sales',
                'password' => Hash::make('password123'),
                'department_id' => $salesDept ? $salesDept->id : null,
                'is_active' => true,
                'confirmed' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'purchasing@halcon.com'],
            [
                'name' => 'Ana Purchasing',
                'password' => Hash::make('password123'),
                'department_id' => $purchasingDept ? $purchasingDept->id : null,
                'is_active' => true,
                'confirmed' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'warehouse@halcon.com'],
            [
                'name' => 'Luis Warehouse',
                'password' => Hash::make('password123'),
                'department_id' => $warehouseDept ? $warehouseDept->id : null,
                'is_active' => true,
                'confirmed' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'route@halcon.com'],
            [
                'name' => 'Mario Route',
                'password' => Hash::make('password123'),
                'department_id' => $routeDept ? $routeDept->id : null,
                'is_active' => true,
                'confirmed' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'inactive@halcon.com'],
            [
                'name' => 'Inactive User',
                'password' => Hash::make('password123'),
                'department_id' => $salesDept ? $salesDept->id : null,
                'is_active' => false,
                'confirmed' => true,
            ]
        );
    }
}
