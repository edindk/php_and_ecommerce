<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'customerID';

    protected $fillable = ['email', 'phone', 'address', 'zipCode', 'isActive', 'createDate', 'modifiedDate', 'firstName', 'lastName'];

    protected $guarded = ['customerID'];
}
