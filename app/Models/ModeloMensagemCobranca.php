<?php

namespace App\Models;

use App\Models\Concerns\PertenceAoUsuarioAtual;
use App\Enums\TipoCobranca;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class ModeloMensagemCobranca extends Model
{
    use HasFactory;
    use PertenceAoUsuarioAtual;

    protected $fillable = ['tipo', 'assunto', 'corpo'];

    protected function casts(): array
    {
        return [
            'tipo' => TipoCobranca::class,
        ];
    }

    protected static function caminhoAteEmpresa(): string
    {
        return 'empresa';
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
