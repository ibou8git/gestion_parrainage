<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Electeur extends Model
{
    use HasFactory;
    
    protected $table = 'Electeur';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function parrainages()
    {
        return $this->hasMany(Parrainage::class, 'electeur_id');
    }
}
