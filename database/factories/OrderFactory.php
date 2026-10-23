<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    public function definition()
    {
        $number = $this->faker->unique()->numberBetween(2000, 9999);
        $status = $this->faker->randomElement(['Ordered', 'In process', 'In route', 'Delivered']);

        return [
            'invoice_number' => 'INV-' . $number,
            'customer_number' => 'CUST-' . $number,
            'customer_name' => $this->faker->company(),
            'fiscal_data' => 'RFC: ' . strtoupper($this->faker->bothify('???######???')) . ', ' . $this->faker->address(),
            'order_datetime' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'delivery_address' => $this->faker->address(),
            'notes' => $this->faker->sentence(),
            'status' => $status,
            'user_id' => User::inRandomOrder()->first()->id ?? null,
            'process_date' => in_array($status, ['In process', 'In route', 'Delivered']) ? now() : null,
            'route_photo' => in_array($status, ['In route', 'Delivered']) ? 'uploads/evidences/route_inv1001.jpg' : null,
            'delivery_photo' => ($status === 'Delivered') ? 'uploads/evidences/delivered_inv1001.jpg' : null,
        ];
    }
}
