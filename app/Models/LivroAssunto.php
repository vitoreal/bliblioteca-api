<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivroAssunto extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'livro_assunto';

    protected $fillable = [
        'autor_id',
        'livro_id',
    ];
}
