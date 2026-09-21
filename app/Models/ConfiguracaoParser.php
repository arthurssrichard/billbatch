<?php

namespace App\Models;

use App\Models\Concerns\PertenceAoUsuarioAtual;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfiguracaoParser extends Model
{
    use HasFactory, PertenceAoUsuarioAtual;

    protected $fillable = ['regex_nome_cliente', 'regex_codigo_barras'];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    protected static function caminhoAteEmpresa(): string
    {
        return 'empresa';
    }
}
