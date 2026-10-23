<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('customer_number');
            $table->string('customer_name');
            $table->text('fiscal_data')->nullable();
            $table->dateTime('order_datetime');
            $table->text('delivery_address');
            $table->text('notes')->nullable();
            $table->string('status')->default('Ordered');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('process_date')->nullable();
            $table->string('route_photo')->nullable();
            $table->string('delivery_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
