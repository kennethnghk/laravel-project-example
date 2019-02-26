<?php

namespace App\Core\Transformers;

use Illuminate\Database\Eloquent\Collection;

class MerchantTransformer extends BaseTransformer
{
    const KEY_NAME = 'name';
    const KEY_EMAIL = 'email';
    const KEY_LATITUDE = 'latitude';
    const KEY_LONGITUDE = 'longitude';

    /**
     * @param \App\Core\Models\Merchant $merchant
     *
     * @return mixed
     */
    public function transform($merchant)
    {
        $coordinates = explode(',', $merchant->getLocation());

        return [
            self::KEY_NAME => $merchant->getName(),
            self::KEY_EMAIL => $merchant->getEmail(),
            self::KEY_LONGITUDE => $coordinates[0] ?: '',
            self::KEY_LATITUDE => $coordinates[0] ?: ''
        ];
    }

}
