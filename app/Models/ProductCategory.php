<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'productCategories';
    protected $primaryKey = 'productCategoryID';

    protected $fillable = ['categoryName', 'imageFile'];

    protected $guarded = ['productCategoryID'];
}
