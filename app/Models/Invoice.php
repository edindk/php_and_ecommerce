<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'invoiceID';

    protected $fillable = ['customerID', 'paidDate', 'total'];

    protected $guarded = ['invoiceID'];
}
