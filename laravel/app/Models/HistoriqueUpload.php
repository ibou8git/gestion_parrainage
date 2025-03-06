<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip',
        'cle_utilisee',
        'date_upload',
        'reussi'
    ];
}

