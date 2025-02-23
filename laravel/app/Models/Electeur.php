<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Electeur extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'carte_identite', 'email', 'telephone', 'bureau_vote'];

    public function parrainages()
    {
        return $this->hasMany(Parrainage::class);
    }
}

