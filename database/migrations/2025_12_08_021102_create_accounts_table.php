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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('account_type_id')
            ->constrained('account_types')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('account_hierarchy_id')
            ->constrained('account_hierarchies')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('account_status_id')
            ->constrained('account_statuses')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();
            $table->timestamps();
            $table->bigInteger('parent_account_id')->nullable();
            $table->string('account_number');
            $table->decimal('balance',15,2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
