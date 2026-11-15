<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->restrictOnDelete();
            $table->foreignId('profile_id')->constrained('profiles')->restrictOnDelete();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->date('starts_at');
            $table->date('expires_at')->index();
            $table->enum('renewal_period', ['monthly', 'quarterly', 'biannual', 'annual'])->default('monthly');
            $table->enum('status', ['active', 'expiring_soon', 'expired', 'cancelled'])->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
