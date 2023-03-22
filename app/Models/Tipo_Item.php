<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_Item extends Model
{
    use HasFactory;
    public $table = 'tipo_item';

    protected $fillable =[
        'nombre_item',
    ];



}
