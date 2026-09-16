<?php

namespace App\Models;

use App\Enums\CanalCobranca;
use App\Models\Concerns\PertenceAoUsuarioAtual;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class Cobranca extends Model
{
    use HasFactory;
    use PertenceAoUsuarioAtual;

    protected $fillable = ['canal_envio', 'contatos_enviados', 'tipo', 'data_envio'];

    protected function casts(): array
    {
        return [
            'canal_envio' => CanalCobranca::class,
            'data_envio' => 'datetime',
        ];
    }

    #[Override]
    protected static function caminhoAteEmpresa(): string
    {
        return 'boleto.empresa';
    }

    public function boleto(): BelongsTo
    {
        return $this->belongsTo(Boleto::class);
    }
}
