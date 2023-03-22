<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    use HasFactory;

    public $table = 'publicacion';

    protected $fillable = [
        'titulo',
        'resumen',
        'fecha',
        'item',

    ];

    public function tipo_item(){
        return $this->hasMany(Tipo_Item::class, 'tipo_item');
    }

}
