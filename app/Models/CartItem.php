<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['sample_quantity'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
