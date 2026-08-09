<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Empresa;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Empresa::class)->constrained()->cascadeOnDelete();
            $table->integer('limite_emails_hora')->default(100);
            $table->string("remetente_nome");
            $table->string("remetente_endereco");
            $table->string("remetente_senha");
            $table->string('smtp_servidor');
            $table->string('smtp_secure');
            $table->string('smtp_porta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
