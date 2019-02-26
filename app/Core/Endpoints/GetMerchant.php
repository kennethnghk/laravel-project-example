<?php

namespace App\Core\Endpoints;

use App\Core\Response;
use App\Core\Transformers\MerchantTransformer;
use Symfony\Component\Console\Input\InputArgument;

class GetMerchant extends Base
{
    const ARGUMENT_MERCHANT_ID = 'merchantId';

    /**
     * Execute the console command.
     *
     * @return \App\Core\Response
     */
    public function fire()
    {
        $resp = $this->makeResponse();

        $merchantId = $this->argument(self::ARGUMENT_MERCHANT_ID);

        if (empty($merchantId)) {
            \Log::debug(__CLASS__ . ' Invalid parameters');
            return $resp->failed(Response::RESP_CODE_BAD_REQUEST, [Response::ERR_CODE_NOT_ENOUGH_PARAM]);
        }

        $merchant = $this->getMerchantRepo()->getMerchantById($merchantId);
        if (empty($merchant)) {
            \Log::debug(sprintf(__CLASS__ . ' merchant=[%s] is not found', $merchantId));

            return $resp->failed(Response::RESP_CODE_NOT_FOUND, [Response::ERR_CODE_RESOURCE_NOT_FOUND]);
        }

        $transformer = new MerchantTransformer();
        $resultData = $transformer->transform($merchant);

        return $resp->succeed()->setData($resultData);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [self::ARGUMENT_MERCHANT_ID, InputArgument::REQUIRED],
        ];
    }
}
