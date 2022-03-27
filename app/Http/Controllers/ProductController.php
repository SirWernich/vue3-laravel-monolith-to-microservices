<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate();

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        return new ProductResource(Product::find($id));
    }

    public function store(Request $request)
    {
        $file = $request->file('image');
        $name = Str::random(20);
        $url = \Storage::putFileAs('images', $file, $name . '.' . $file->extension());

        $product = Product::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'image' => env('APP_URL') . '/' . $url
        ]);

        return response($product, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        Product::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
