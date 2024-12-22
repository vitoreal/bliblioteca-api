<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    
    public $timestamps = false;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'autor';

    protected $fillable = ['nome'];
}
