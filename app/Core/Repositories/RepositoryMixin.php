<?php

namespace App\Core\Repositories;

trait RepositoryMixin
{

    /** @var \App\Core\Repositories\EloquentMerchantRepository */
    protected $merchantRepo;

    protected function getMerchantRepo()
    {
        if (!$this->merchantRepo) {
            $this->merchantRepo = \App::make('App\Core\Repositories\EloquentMerchantRepository');
        }
        return $this->merchantRepo;
    }
}
