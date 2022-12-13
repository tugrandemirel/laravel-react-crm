<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductProperty;
use Illuminate\Http\Request;
use App\Helpers\FileUpload;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = request()->user();
        return response()->json([
            'success'=>true,
            'user'=>$user
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = request()->user();
        $categories = Category::where('userId',$user->id)->get();
        return response()->json([
            'success'=>true,
            'categories'=>$categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = request()->user();
        $all = $request->all();
        $file = (isset($all['file'])) ? $all['file'] : [];
        // properties varsa json array olarak al

        $properties = (isset($all['property'])) ? json_decode($all['property'], true) : [];
        unset($all['file']);
        unset($all['property']);
        $all['userId'] = $user->id;
        $create = Product::create($all);
        if ($create){
            foreach ($file as $item){
                $upload = FileUpload::newUpload(rand(1,9000), 'products', $item, 0);
                ProductImage::create([
                    'productId'=>$create->id,
                    'path'=>$upload
                ]);
            }
            foreach ($properties as $prop){
                ProductProperty::create([
                    'productId'=>$create->id,
                    'property'=>$prop['property'],
                    'value'=>$prop['value']
                ]);
            }
            return response()->json([
                'success'=>true,
                'message'=>'Ürün başarıyla eklendi'
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Ürün eklenirken bir hata oluştu'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
