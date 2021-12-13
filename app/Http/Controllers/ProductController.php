<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showProducts()
    {
        // Henter alle produkter.
        $products = Product::all();

        // Henter alle kategorier.
        $categories = ProductCategory::all();

        // Loopes igennem products.
        foreach ($products as $product) {
            // Finde  kategorien ud fra kategori id'et på produktet.
            $category = ProductCategory::find($product->productCategoryID);

            // categoryName property opdateres med kategori navn.
            $product->categoryName = $category->categoryName;
        }

        // Renderer products siden indeholdende data for produkter og kategorier.
        return view('pages.products', ['products' => $products, 'categories' => $categories]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request)
    {
        // Validering af inputfelterne.
        $request->validate([
            'name' => 'required|max:255',
            'category' => 'required',
            'description' => 'required|max:2000',
            'price' => 'required|max:255',
            'inStock' => 'required|max:255'
        ]);

        // Finder produktet ud fra id'et.
        $product = Product::find($request->productID);

        // Finder kategori ud fra kategori navnet.
        $category = ProductCategory::where('categoryName', $request->category)->first();

        // Opdatering af produktet.
        $product->name = $request->name;
        $product->productCategoryID = $category->productCategoryID;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->inStock = $request->inStock;

        // Gemmer produktet.
        $product->save();

        return redirect()->route('products')->with('message', 'Produkt opdateret');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        // Validering af inputfelterne.
        $request->validate([
            'name' => 'required|max:255',
            'category' => 'required',
            'description' => 'required|max:2000',
            'price' => 'required|max:255',
            'inStock' => 'required|max:255',
            'partNumber' => 'required',
            'img' => 'required'
        ]);

        // Finder kategori ud fra kategori navnet.
        $category = ProductCategory::where('categoryName', $request->category)->first();

        // Opbevaring af billedet på disken.
        $path = Storage::disk('product_images')->putFile('', $request->img);

        // Oprettels af product.
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        // Finder produktet ud fra id'et.
        $product = Product::find($request->productID);
        // Kalder delete() metoden på det fundne produkt.
        $product->delete();

        return redirect()->route('products')->with('message', 'Produkt slettet');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        // Finder produktet indeholdende specifikke bogstaver eller ord.
        $products = Product::where('name', 'LIKE', '%' . $request->name . '%')->get();

        // For hvert produkt.
        foreach ($products as $product) {
            // Finder kategori navnet ud fra id'et.
            $category = ProductCategory::find($product->productCategoryID);

            // categoryName property opdateres med kategori navn.
            $product->categoryName = $category->categoryName;
        }

        return $products;
    }
}
