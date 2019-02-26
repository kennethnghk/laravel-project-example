<?php

namespace App\Core\Endpoints;

use App\Core\Transformers\MerchantTransformer;

class GetAllMerchants extends Base
{
    const KEY_MERCHANTS = 'merchants';

    /**
     * Execute the console command.
     *
     * @return \App\Core\Response
     */
    public function fire()
    {
        $resp = $this->makeResponse();

        $merchants = $this->getMerchantRepo()->getAllMerchants();

        $resultData = [
            self::KEY_MERCHANTS => []
        ];

        $transformer = new MerchantTransformer();
        foreach ($merchants as $merchant) {
            $resultData[self::KEY_MERCHANTS][] = $transformer->transform($merchant);
        }

        return $resp->succeed()->setData($resultData);
    }
}
