<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    use HasFactory;
    
    protected $fillable = ['nome', 'uuid', 'ultima_atividade'];

    protected function casts(): array
    {
        return [
            'ultima_atividade' => 'datetime',
        ];
    }

    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class);
    }
}
