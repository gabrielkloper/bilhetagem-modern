<?php

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
        Schema::table('entradas', function (Blueprint $table) {
            $table->enum('forma_pagamento', ['dinheiro', 'cartao_debito', 'cartao_credito', 'pix', 'gratuito'])->default('dinheiro')->after('datahora_autorizacao');
            $table->decimal('valor_pago', 10, 2)->default(0.00)->after('forma_pagamento');
            $table->decimal('valor_troco', 10, 2)->default(0.00)->after('valor_pago');
            $table->string('observacoes_pagamento', 500)->nullable()->after('valor_troco');
            $table->boolean('pagamento_confirmado')->default(true)->after('observacoes_pagamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entradas', function (Blueprint $table) {
            $table->dropColumn(['forma_pagamento', 'valor_pago', 'valor_troco', 'observacoes_pagamento', 'pagamento_confirmado']);
        });
    }
};
