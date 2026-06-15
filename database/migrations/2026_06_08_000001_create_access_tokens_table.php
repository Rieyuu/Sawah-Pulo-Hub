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
        if (! Schema::hasTable('access_tokens')) {
            Schema::create('access_tokens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('token');
                $table->text('refresh_token');
                $table->timestamp('expires_at');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('revoked_at')->nullable();

                $table->index('token');
                $table->index('refresh_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_tokens');
    }
};
