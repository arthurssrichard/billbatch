<?php

namespace App\Models;

use App\Models\Concerns\PertenceAoUsuarioAtual;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Boleto extends Model
{
    use HasFactory;
    use PertenceAoUsuarioAtual;

    protected $fillable = ['empresa_id', 'codigo_barras', 'grupo', 'caminho_arquivo', 'enviado', 'pago', 'data_emissao'];

    protected function casts(): array
    {
        return [
            'data_emissao' => 'datetime',
        ];
    }

    #[Override]
    protected static function caminhoAteEmpresa(): string
    {
        return 'empresa';
    }

    public function cobrancas(): HasMany
    {
        return $this->hasMany(Cobranca::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
