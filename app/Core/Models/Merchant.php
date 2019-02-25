<?php

namespace App\Core\Models;

class Merchant extends Base
{
    const TABLE_NAME = 'merchant';

    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_EMAIL = 'email';
    const COLUMN_LOCATION = 'location';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME,
        self::COLUMN_EMAIL,
        self::COLUMN_LOCATION
    ];

    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::COLUMN_ID;
}
