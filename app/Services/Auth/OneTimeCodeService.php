<?php

namespace App\Services\Auth;

use App\Models\Miscellaneous;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class OneTimeCodeService
{

    public function isAuthValid(string $secretToken, string $oneTimeCode): bool | string
    {
        if ($secretToken !== env('SECRET_APP_TK')) {
            return false;
        }

        //todo: create table on the database that must be used to save miscelanius data such as this encrypted code
        $fetchCode = Miscellaneous::where('name', 'OneTimeCode')->first();

        if (!is_object($fetchCode) || !isset($fetchCode)) {
            return false; // EXCEPTION must request a new otc
        }

        if ($this->isOtcExpired($fetchCode->updated_at)) {
            return false; // EXCEPTION must request a new otc
        }

        if (Crypt::decrypt($oneTimeCode) !== Crypt::decrypt($fetchCode->value)) {
            return false; // EXCEPTION Invalid OTC Must request a new one or invalid one time code
        }

        return true;
    }

    private function isOtcExpired(string $registerDate): bool
    {
        $registerDate = Carbon::parse($registerDate);
        $nowDate = Carbon::now();

        return $registerDate->diffInMinutes($nowDate) >= (int)env('OTC_EXP_TIME') ? true : false;
    }

    /**
     * Generate a new key for first auth layer.
     *
     * @return string
     */
    public function generateNewOneTimeCode(string $secretToken)
    {
        if ($secretToken !== env('SECRET_APP_TK')) {
            return false;
        }

        $fetchCode = Miscellaneous::where('name', 'OneTimeCode')->first();

        if (!is_object($fetchCode) || !isset($fetchCode)) {
            $fetchCode = $this->generateNewOTC();
        }

        if ($this->isOtcExpired($fetchCode->updated_at)) {
            $fetchCode = $this->updateOTC();
        }

        return $fetchCode->value;
    }

    private function generateNewOTC(): Miscellaneous
    {
        try {
            $secret = Str::uuid()->toString();
            $encryptedToken = Crypt::encryptString($secret);

            return Miscellaneous::create([
                'name' => 'OneTimeCode',
                'value' => $encryptedToken
            ]);
        } catch (\Throwable $th) {
            throw new \Exception('Error creating OTC: ' . $th->getMessage());
        }
    }

    private function updateOTC(): Miscellaneous
    {
        try {
            $secret = Str::uuid()->toString();
            $encryptedToken = Crypt::encryptString($secret);

            $otc = Miscellaneous::where('name', 'OneTimeCode')->firstOrFail();
            $otc->value = $encryptedToken;
            $otc->save();

            return $otc;
        } catch (\Throwable $th) {
            //todo: Handle the exception, e.g., log it or throw a custom exception
            throw new \Exception('Error updating OTC: ' . $th->getMessage());
        }
    }
}
