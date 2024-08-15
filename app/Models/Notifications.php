<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifications extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'reservation_id',
        'message',
        'send_date',
        'status'
    ];
}
