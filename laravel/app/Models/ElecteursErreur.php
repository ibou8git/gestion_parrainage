<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElecteursErreur extends Model
{
    use HasFactory;

    protected $table = 'electeurs_erreurs'; // Assurer que Laravel pointe sur la bonne table
    protected $fillable = ['upload_id', 'carte_identite', 'num_electeur', 'erreur'];
}
