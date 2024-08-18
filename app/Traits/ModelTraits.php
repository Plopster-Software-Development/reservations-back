<?php

namespace App\Traits;

use App\Helpers\Encryption;

trait ModelTraits
{
    protected $encryptAttributes = [
        'client_secret',
        'api_key'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->encryptAttributes();
        });
    }

    protected function encryptAttributes()
    {
        $encryption = new Encryption;

        foreach ($this->encryptAttributes as $attribute) {
            if (isset($this->attributes[$attribute])) {
                $this->attributes[$attribute] = $encryption->encrypt($this->attributes[$attribute]);
            }
        }
    }
}
