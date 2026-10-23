<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;

class OrderEvidenceFactory extends Factory
{
    public function definition()
    {
        $stage = $this->faker->randomElement(['In route', 'Delivered']);
        $photo = $stage === 'In route' ? 'uploads/evidences/route_inv1001.jpg' : 'uploads/evidences/delivered_inv1001.jpg';

        return [
            'order_id' => Order::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? null,
            'stage' => $stage,
            'photo_path' => $photo,
            'notes' => $this->faker->sentence(),
        ];
    }
}
