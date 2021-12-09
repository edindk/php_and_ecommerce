<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{

    public function showProducts()
    {
        $products = Product::all();
        $categories = ProductCategory::all();
        foreach ($products as $product) {
            $category = ProductCategory::find($product->productCategoryID);
            $product->categoryName = $category->categoryName;
        }
        return view('pages.products', ['products' => $products, 'categories' => $categories]);
    }

    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category' => 'required',
            'description' => 'required|max:2000',
            'price' => 'required|max:255',
            'inStock' => 'required|max:255'
        ]);

        $product = Product::find($request->productID);
        $category = ProductCategory::where('categoryName', $request->category)->first();

        $product->name = $request->name;
        $product->productCategoryID = $category->productCategoryID;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->inStock = $request->inStock;

        $product->save();

        return redirect()->route('products')->with('message', 'Produkt opdateret');
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category' => 'required',
            'description' => 'required|max:2000',
            'price' => 'required|max:255',
            'inStock' => 'required|max:255',
            'partNumber' => 'required',
            'img' => 'required'
        ]);

        $category = ProductCategory::where('categoryName', $request->category)->first();

        $path = Storage::disk('product_images')->putFile('', $request->img);

        Product::create([
            'name' => $request->name,
            'productCategoryID' => $category->productCategoryID,
            'description' => $request->description,
            'price' => $request->price,
            'inStock' => $request->inStock,
            'partNumber' => $request->partNumber,
            'imageFile' => 'storage/product_images/' . $path
        ]);

        return redirect()->route('products')->with('message', 'Produkt oprettet');
    }

    public function delete(Request $request)
    {
        $product = Product::find($request->productID);
        $product->delete();

        return redirect()->route('products')->with('message', 'Produkt slettet');
    }

    public function search(Request $request)
    {
        $products = Product::where('name', 'LIKE', '%' . $request->name . '%')->get();
        foreach ($products as $product) {
            $category = ProductCategory::find($product->productCategoryID);
            $product->categoryName = $category->categoryName;
        }
        
        return $products;
    }
}
