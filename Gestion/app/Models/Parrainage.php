<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parrainage extends Model
{
    use HasFactory;

    protected $table = 'Parrainage'; // Laravel utilise normalement des noms en minuscule et au pluriel

    protected $fillable = [
        'date_parrainage',
        'electeur_id',
        'candidat_id',
        'statut',
    ];

    public $timestamps = false;
}
