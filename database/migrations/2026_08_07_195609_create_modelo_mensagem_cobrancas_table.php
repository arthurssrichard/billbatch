<?php

use App\Models\Empresa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modelo_mensagem_cobrancas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Empresa::class)->constrained()->cascadeOnDelete();
            $table->string('tipo'); // Primeiro envio, aviso, cobranca, etc
            $table->string('assunto');
            $table->string('corpo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelo_mensagem_cobrancas');
    }
};
