<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEvidence extends Model
{
    use HasFactory;

    protected $table = 'order_evidences';

    protected $fillable = [
        'order_id',
        'user_id',
        'stage',
        'photo_path',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
