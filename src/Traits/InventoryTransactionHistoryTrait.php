<?php

namespace Stevebauman\Inventory\Traits;

use Stevebauman\Inventory\Helper;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Inventory\Traits\UserIdentificationTrait;

trait InventoryTransactionHistoryTrait
{
    use UserIdentificationTrait;

    /**
     * Make sure we try and assign the current user if enabled.
     *
     * @return void
     */
    public static function bootInventoryTransactionHistoryTrait()
    {
        static::creating(function (Model $model) {
            $model->{static::getForeignUserKey()} = static::getCurrentUserId();
        });
    }

    /**
     * The belongsTo stock relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    abstract public function transaction();
}
