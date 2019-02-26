<?php

namespace App\Core\Repositories;

use App\Core\Models\Merchant;

class EloquentMerchantRepository extends EloquentBaseRepository
{
    public function __construct()
    {
        parent::__construct('App\Core\Models\Merchant');
    }

    /**
     * Get merchant by ID
     *
     * @param int $merchantId
     *
     * @return \Illuminate\Database\Eloquent\Model|\App\Core\Models\Merchant|null $merchant
     */
    public function getMerchantById($merchantId)
    {
        if (empty($merchantId)) {
            return null;
        }

        return $this->where(Merchant::COLUMN_ID, $merchantId)->first();
    }
}
