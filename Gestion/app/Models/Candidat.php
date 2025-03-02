<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;

    protected $table = 'Candidat';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function parrainages()
    {
        return $this->hasMany(Parrainage::class, 'candidat_id');
    }
}
