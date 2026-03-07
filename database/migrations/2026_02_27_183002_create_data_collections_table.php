<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up()
  {
    Schema::create('data_collections', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete()->cascadeOnUpdate();
      $table->foreignId('data_type_id')->constrained('data_types')->cascadeOnDelete()->cascadeOnUpdate();

      $table->string('name');
      $table->string('slug');

      $table->enum('type', ['manual', 'dynamic'])->default('manual');

      $table->json('conditions')->nullable(); // dynamic collections
      $table->enum('conditions_logic', ['and', 'or'])->default('and');

      $table->text('description')->nullable();
      $table->boolean('is_active')->default(true);

      // flexible future settings
      $table->json('settings')->nullable();

      $table->timestamps();

      $table->unique(['project_id', 'slug']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('data_collections');
  }
};
