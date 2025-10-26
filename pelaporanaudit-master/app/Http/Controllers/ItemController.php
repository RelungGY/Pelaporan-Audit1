<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function productInfo($id)
        {
            $product = Item::find($id);

            if ($product) {
                return response()->json([
                    'success' => true,
                    'product' => $product
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ]);
            }
        }
}