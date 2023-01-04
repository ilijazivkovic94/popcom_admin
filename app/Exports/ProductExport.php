<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Auth;
use DB;

use App\Models\Product;
use App\Models\ProductVariant;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        // return Product::all();
        $accountID = Auth::user()->account_id;
        $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
        if($accountType == 'sub'){
             $account_id = [Auth::user()->account_id];
            $parentAccountID = Auth::user()->accountDetails()->pluck('account_id_parent')->first();
                array_push($account_id, $parentAccountID);

            return ProductVariant::select('product_variants.variant_sku', 'products.product_name', 'products.product_image', 'products.product_description','product_variants.variant_name','product_variants.variant_price','product_variants.product_identifier','products.account_id',DB::raw('(select price from myproducts where variant_id=product_variants.product_variant_id and product_id=products.product_id limit 1) as price,(SELECT account_name from accounts where account_id=products.account_id) as parent_name,IF(products.product_retired_dt IS NULL,"N","Y") as retired_yn,products.product_retired_dt'))
            ->rightjoin('products', 'products.product_id', '=', 'product_variants.product_id')
            ->whereIn('products.account_id', $account_id)
            ->orderby('products.product_id','desc')
            ->get(); 

        }else{
            return ProductVariant::select('product_variants.*', 'products.product_name', 'products.product_image', 'products.product_description',DB::raw('IF(products.product_retired_dt IS NULL,"N","Y") as retired_yn,products.product_retired_dt'))
            ->rightjoin('products', 'products.product_id', '=', 'product_variants.product_id')
            ->where('products.account_id', $accountID)
            ->orderby('products.product_id','desc')
            ->get();   
        }
       
    }

    public function headings(): array {
        $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
        if($accountType == 'sub'){
            return [
                'Product Identifier',
                'Product Name',
                'Product Description',
                'Owner',
                'Variant Type',
                'Variant Name',
                'Default Price',
                'Retired',
                'Retired Date'
            ];
        }else{
            return [
                'Product Identifier',
                'Product Name',
                'Product Description',
                'Variant Type',
                'Variant Name',
                'Default Price',
                'Retired',
                'Retired Date'
            ];
        }
        
    }

     public function prepareRows($rows): array
    {
       
        return array_map(function ($product) {
            $product->product_description = strip_tags($product->product_description);
             $accountType  = Auth::user()->accountDetails()->pluck('account_type')->first();
            if($accountType == 'sub'){
                
                $product->variant_price = !empty($product->price) ? $product->price : $product->variant_price;

                 $parentAccountID = Auth::user()->accountDetails()->pluck('account_id_parent')->first();

                 if($product->account_id == $parentAccountID){
                        $parent_name = $product->parent_name; 
                 }else{
                    $parent_name = 'Self';
                 }

                 $product->parent_name = $parent_name;
            }

            if(!empty($product->product_retired_dt)){
                $product->product_retired_dt = date('m/d/Y',strtotime($product->product_retired_dt));
            }

            return $product;
        }, $rows);
    }

    public function map($product): array {
        $accountType    = Auth::user()->accountDetails()->pluck('account_type')->first();
         if($accountType == 'sub'){
            return [
                $product->product_identifier,
                $product->product_name,
                $product->product_description,
                $product->parent_name,
                $product->variant_sku,
                $product->variant_name,
                '$'.$product->variant_price,
                $product->retired_yn,
                $product->product_retired_dt  
            ];
        }else{
            return [
                $product->product_identifier,
                $product->product_name,
                $product->product_description,
                $product->variant_sku,
                $product->variant_name,
                '$'.$product->variant_price,
                $product->retired_yn,
                $product->product_retired_dt    
            ];
        }
        
    }
}
