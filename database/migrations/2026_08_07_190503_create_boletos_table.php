<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Cliente;
use App\Models\Empresa;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cliente::class)->contrained()->cascadeOnDelete();
            $table->foreignIdFor(Empresa::class)->contrained()->cascadeOnDelete();
            $table->string('codigo_barras');
            $table->string('grupo');
            $table->string('caminho_arquivo');
            $table->bool('enviado')->default(false);
            $table->bool('pago')->default(false);
            $table->datetime('data_emissao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletos');
    }
};
