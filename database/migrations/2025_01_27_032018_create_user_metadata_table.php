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
        Schema::create('user_metadata', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            // Chave estrangeira para users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Índices para otimização
            $table->unique(['user_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_metadata');
    }
};
