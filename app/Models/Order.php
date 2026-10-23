<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'customer_number',
        'customer_name',
        'fiscal_data',
        'order_datetime',
        'delivery_address',
        'notes',
        'status',
        'user_id',
        'process_date',
        'route_photo',
        'delivery_photo',
    ];

    protected $casts = [
        'order_datetime' => 'datetime',
        'process_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evidences()
    {
        return $this->hasMany(OrderEvidence::class);
    }
}
