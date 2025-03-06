<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempElecteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'carte_identite',
        'num_electeur',
        'email',
        'telephone',
        'bureau_vote',
        'valide'
    ];
}

