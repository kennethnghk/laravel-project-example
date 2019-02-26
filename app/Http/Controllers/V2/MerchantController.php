<?php

namespace App\Http\Controllers\V2;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Core\Endpoints\GetMerchant;
use App\Core\Endpoints\GetAllMerchants;

class MerchantController extends BaseController
{
    /**
     * Get merchant
     *
     * @param Request $request
     * @param int $merchantId
     *
     * @return \Illuminate\Http\Response
     */
    public function getMerchant(Request $request, $merchantId)
    {
        /** @var GetMerchant $endpoint */
        $endpoint = app(GetMerchant::class);
        $arguments = [
            GetMerchant::ARGUMENT_MERCHANT_ID => $merchantId,
        ];

        $endpoint->setParameters($arguments);
        $resp = $endpoint->fire();

        return $resp->toApiOutput();
    }

    /**
     * Get all merchants
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getMerchants(Request $request)
    {
        /** @var GetMerchant $endpoint */
        $endpoint = app(GetAllMerchants::class);

        $endpoint->setParameters();
        $resp = $endpoint->fire();

        return $resp->toApiOutput();
    }
}
