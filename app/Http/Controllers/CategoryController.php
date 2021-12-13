<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showCategories(Request $request)
    {
        // Henter alle produkt kategorier fra DB.
        $category = ProductCategory::all();

        //Dummy product. Den bruges som værende det 0. produkt for at kunne vurdere om der er tale om en create når vi fylder modal ud.
        $dummyCategory = new ProductCategory();
        $dummyCategory->categoryID = 0;

        //Bruges til at bestemme hvilket kolonne produkterne skal sorteres efter samt om de skal vises fra top til bund eller omvendt.
        $orderValue = $request->query('order');
        $sortValue = $request->query('column');

        // Hvis orderValue er lig med "desc", sættes order variablen til "desc". Hvis ikke sættes den til "asc".
        $order = isset($orderValue) && $orderValue == "desc" ? "desc" : "asc";
        // Hvis sortValue er sat, sættes sort variablen til sortValue. Hvis ikke sættes den til 'categoryName'.
        $sort = isset($sortValue) ? $sortValue : 'categoryName';

        // Produkt kategorierne hentes og sorteres ud fra variablerne order og sort.
        $categories = ProductCategory::orderBy($sort, $order)->get()->values()->all();

        // Indsætter ny kategori i kollektionen startende fra 0.
        array_unshift($categories, $dummyCategory);

        // Renderer kategori siden indeholdende kategorier.
        return view('pages.categories', ['categories' => $categories]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // Validering af categoryName samt img.
        $this->validate($request, [
            'categoryName' => 'required',
            'img' => 'required',
        ]);

        // Opbevaring af billedet på disken.
        $path = Storage::disk('category_images')->putFile('', $request->img);

        // Oprettels af productCategory.
        ProductCategory::create([
            'categoryName' => $request->categoryName,
            'imageFile' => 'storage/product_images/' . $path,
        ]);

        // Redirecter til kategori siden.
        return redirect()->route('categories')->with('message', 'Kategori oprettet');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request)
    {
        // Validering af categoryName.
        $this->validate($request, [
            'categoryName' => 'required',
        ]);

        // Finder kategori i DB ud fra id'et.
        $category = ProductCategory::find($request->productCategoryID);

        // Kategori navnet opdateres med den nye værdi.
        $category->categoryName = $request->categoryName;

        // Metoden save() kaldes for at gemme i DB.
        $category->save();

        // Redirecter til kategori siden.
        return redirect()->route('categories')->with('message', 'Kategori opdateret');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Finder kategorien ud fra id'et.
        $category = ProductCategory::findOrFail($id);

        // Kalder delete() metoden på den fundne kategori.
        $category->delete();

        // Redirecter til kategori siden.
        return redirect()->route('categories')->with('message', 'Kategori slettet');
    }
}
