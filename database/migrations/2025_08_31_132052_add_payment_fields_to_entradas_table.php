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
            $table->string('forma_pagamento_extra')->nullable()->after('pgto_extra_valor');
            $table->string('payment_id')->nullable()->after('forma_pagamento_extra');
            $table->string('payment_status')->nullable()->after('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entradas', function (Blueprint $table) {
            $table->dropColumn(['forma_pagamento_extra', 'payment_id', 'payment_status']);
        });
    }
};
