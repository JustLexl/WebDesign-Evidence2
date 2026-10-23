<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            [
                'name' => 'Admin',
                'description' => 'System administration and user management',
            ],
            [
                'name' => 'Sales',
                'description' => 'Order intake, customer communication, and physical invoice coordination',
            ],
            [
                'name' => 'Purchasing',
                'description' => 'Supplier procurement and material purchases for low-stock items',
            ],
            [
                'name' => 'Warehouse',
                'description' => 'Inventory preparation, order picking, and vehicle loading',
            ],
            [
                'name' => 'Route',
                'description' => 'Distribution logistics, order transport, and delivery verification',
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(['name' => $department['name']], $department);
        }
    }
}
