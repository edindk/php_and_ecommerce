<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function showCategories(Request $request)
    {
        $category = ProductCategory::all();

        //Dummy product. Den bruges som værende det 0. produkt for at kunne vurdere om der er tale om en create når vi fylder modal ud.
        $dummyCategory = new ProductCategory();
        $dummyCategory->categoryID = 0;

        //Bruges til at bestemme hvilket kolonne produkterne skal sorteres efter samt om de skal vises fra top til bund eller omvendt.
        $orderValue = $request->query('order');
        $sortValue = $request->query('column');

        $order = isset($orderValue) && $orderValue == "desc" ? "desc" : "asc";
        $sort = isset($sortValue) ? $sortValue : 'categoryName';

        $categories = ProductCategory::orderBy($sort, $order)->get()->values()->all();

        array_unshift($categories, $dummyCategory);

        return view('pages.categories', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
            'img' => 'required',
        ]);

        $path = Storage::disk('category_images')->putFile('', $request->img);

        ProductCategory::create([
            'categoryName' => $request->categoryName,
            'imageFile' => 'storage/product_images/' . $path,
        ]);

        return redirect()->route('categories')->with('message', 'Kategori oprettet');
    }

    public function edit(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
        ]);

        $category = ProductCategory::find($request->productCategoryID);

        $category->categoryName = $request->categoryName;

        $category->save();

        return redirect()->route('categories')->with('message', 'Kategori opdateret');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $this->validate($request, [
            'category_name' => 'required',
        ]);

        $input = $request->all();

        $category->fill($input)->save();

        return redirect()->route('category.index');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);

        $category->delete();

        return redirect()->route('categories')->with('message', 'Kategori slettet');
    }
}
