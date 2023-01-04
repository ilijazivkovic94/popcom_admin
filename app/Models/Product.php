<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Kiosk; 
use App\Models\KioskProduct;
use Auth;

class Product extends Model
{
    use HasFactory;
    protected $table    = 'products';
    public $primaryKey  = 'product_id';
    public $timestamps  = false;
    
    protected $fillable = [
        'account_id',
        'product_name',
        'product_image',
        'product_description',
        'product_status',
        'is_deleted',
        'created_at',
        'modified_at',
        'product_retired_dt'
    ];

    public function Product_Image(){
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function Product_Variant(){
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }

    //DataTable
    public function getKioskName($accountID, $productID){
        $machineName = '';
        
        // $getData = Kiosk::where('account_id', $accountID)->get();
        // if(isset($getData) && count($getData) > 0){
        //     foreach ($getData as $key => $value) {
                
        //     }
        // }
        $check = ProductVariant::select('product_variants.product_variant_id', 'kiosk_product.kiosk__id','kiosks.kiosk_identifier')
                ->join('products', 'products.product_id', '=', 'product_variants.product_id')
                ->join('kiosk_product', 'kiosk_product.product_variant_id', '=', 'product_variants.product_variant_id')
                ->join('kiosks','kiosks.kiosk_id','=','kiosk_product.kiosk__id')
                ->where('products.product_id', $productID)
                ->groupBy('kiosks.kiosk_id')
                ->get();
        if($check->count() > 0){
            foreach ($check as $key => $value) {
                $machineName .= $value->kiosk_identifier.', ';
            }
        }
        return rtrim($machineName, ', ');
    }
}
