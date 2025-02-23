<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_carte', 'nom', 'prenom', 'date_naissance',
        'email', 'telephone', 'parti_politique', 'slogan', 'photo',
        'couleur_1', 'couleur_2', 'couleur_3', 'url_infos', 'code_securite'
    ];
}
