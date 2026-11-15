<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_account_id')->constrained('provider_accounts')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('pin', 10)->nullable();
            $table->enum('status', ['free', 'occupied', 'disabled'])->default('free')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
