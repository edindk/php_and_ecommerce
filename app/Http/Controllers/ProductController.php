<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required',
        ]);

        $input = $request->all();

        \Products::create($input);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products = Products::findOrFail($id);
        return view('products.show', compact('products', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
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

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Products = Products::findOrFail($id);

        $this->validate($request, [
            'product_name' => 'required',
        ]);

        $input = $request->all();

        $Products->fill($input)->save();

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $products = Products::findOrFail($id);

        $products->delete();

        return redirect()->route('products.index');
    }
}
