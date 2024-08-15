<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservations extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'client_id',
        'table_id',
        'reservation_date',
        'status'
    ];
}
