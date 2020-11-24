<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allow no user
    |--------------------------------------------------------------------------
    |
    | Allows inventory changes to occur without a user responsible.
    |
    */

    'allow_no_user' => true,

    /*
    |--------------------------------------------------------------------------
    | Allow Duplicate Movements
    |--------------------------------------------------------------------------
    |
    | Allows inventory stock movements to have the same before and after quantity.
    |
    */

    'allow_duplicate_movements' => true,

    /*
    |--------------------------------------------------------------------------
    | Rollback Cost
    |--------------------------------------------------------------------------
    |
    | For example, if the movement's cost that is being rolled
    | back is 500, the rolled back movement will be -500.
    |
    */

    'rollback_cost' => true,

    /*
    |--------------------------------------------------------------------------
    | Skus Enabled
    |--------------------------------------------------------------------------
    |
    | Enables SKUs to be automatically generated on item creation.
    |
    */

    'skus_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Sku Prefix Length
    |--------------------------------------------------------------------------
    |
    | The sku prefix length, not including the code for example:
    |
    | An item with a category named 'Sauce', the sku prefix generated will be: SAU
    |
    */

    'sku_prefix_length' => 3,

    /*
    |--------------------------------------------------------------------------
    | Sku Code Length
    |--------------------------------------------------------------------------
    |
    | The sku code length, not including prefix for example:
    |
    | An item with an ID of 1 (one) the sku code will be: 000001
    |
    */

    'sku_code_length' => 6,

    /*
     * The sku separator for use in separating the prefix from the code.
     *
     * For example, if a hyphen (-) is inserted in the string below, a possible
     * SKU might be 'DRI-00001'
     *
     * @var string
     */

    /*
    |--------------------------------------------------------------------------
    | Sku Separator
    |--------------------------------------------------------------------------
    |
    | The sku separator for use in separating the prefix from the code.
    |
    | For example, if a hyphen (-) is inserted in the string
    | below, a possible SKU might be 'DRI-00001'
    |
    */

    'sku_separator' => '',

    /*
    |--------------------------------------------------------------------------
    | Sku Prefix Column
    |--------------------------------------------------------------------------
    |
    | The sku prefix column for use in generating an SKU.
    |
    | For example, if another prefix (like a numeric) should be used as the prefix
    | change the value to the desired column name.
    |
    */

    'sku_prefix_column' => 'code',

    /*
     |--------------------------------------------------------------------------
     | Foreign User Key
     |--------------------------------------------------------------------------
     | The column name that is used to determine which user created the record.
     |
     | @var string
     */

    'foreign_user_key' => 'created_by',

];
