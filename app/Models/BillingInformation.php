<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingInformation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'owner_name',
        'owner_lastname',
        'address',
        'city',
        'province',
        'country',
        'zip_code',
        'phoneNumber',
        'email'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
