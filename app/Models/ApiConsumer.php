<?php

namespace App\Models;

use App\Traits\ModelTraits;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConsumer extends Model
{
    use HasFactory, HasUuids, ModelTraits;

    protected $fillable = [
        'name',
        'client_secret',
        'api_key'
    ];
}
