<?php

namespace App\Models;

use App\Models\Concerns\PertenceAoUsuarioAtual;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class Contato extends Model
{
    use HasFactory;
    use PertenceAoUsuarioAtual;

    protected $fillable = ['endereco_email'];

    #[Override]
    protected static function caminhoAteEmpresa(): string
    {
        return 'cliente.empresa';
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
