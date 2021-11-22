<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'productID';

    protected $fillable = ['categoryID', 'name', 'description', 'partNumber', 'price', 'inStock', 'isActive', 'imageFile', 'createDate', 'modifiedDate'];

    protected $guarded = ['productID'];
}
