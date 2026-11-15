<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('account_identifier', 50)->nullable();
            $table->string('email', 255)->index();
            $table->text('password');
            $table->decimal('cost', 8, 2)->default(0.00);
            $table->date('billing_start_date')->nullable();
            $table->date('billing_due_date')->index();
            $table->unsignedTinyInteger('max_profiles')->default(1);
            $table->enum('status', ['active', 'suspended', 'cancelled'])->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_accounts');
    }
};
