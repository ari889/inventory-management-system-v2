<?php

namespace Modules\Product\Entities;

use Modules\Base\Entities\BaseModel;

class WarehouseProduct extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'warehouse_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['warehouse_id', 'product_id', 'qty'];

    /**
     * relationship with product
     */
    public function  product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
