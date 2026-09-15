<?php

namespace App\Models;

use App\Enums\LogStatus;
use App\Models\Concerns\PertenceAoUsuarioAtual;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class Log extends Model
{
    use HasFactory;
    use PertenceAoUsuarioAtual;

    protected $fillable = ['status', 'arquivo_origem', 'nome', 'mensagem'];

    protected function casts(): array
    {
        return [
            'status' => LogStatus::class,
        ];
    }

    #[Override]
    protected static function caminhoAteEmpresa(): string
    {
        return 'empresa';
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
