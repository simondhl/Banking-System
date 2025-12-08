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
        Schema::create('accounts_transactions', function (Blueprint $table) {
            $table->id();
             $table->foreignId('account_id')
            ->constrained('accounts')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('transaction_id')
            ->constrained('transactions')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->string('sending_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_transactions');
    }
};
