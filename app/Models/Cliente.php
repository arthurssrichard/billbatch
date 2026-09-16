<?php

namespace App\Models;

use App\Models\Concerns\PertenceAoUsuarioAtual;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Cliente extends Model
{
    use HasFactory;
    use PertenceAoUsuarioAtual;

    protected $fillable = ['identificador_externo', 'nome', 'canais_envio', 'cnpj'];

    protected function casts(): array
    {
        return [
            'canais_envio' => 'array',
        ];
    }

    #[Override]
    protected static function caminhoAteEmpresa(): string
    {
        return 'empresa';
    }

    public function contatos(): HasMany
    {
        return $this->hasMany(Contato::class);
    }

    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
