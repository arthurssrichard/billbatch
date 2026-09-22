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
        Schema::create('configuracao_parsers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Empresa::class)->unique()->constrained();
            $table->string('regex_nome_cliente');
            $table->string('regex_codigo_barras');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracaoes_parser');
    }
};
