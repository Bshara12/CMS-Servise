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
    Schema::create('data_types', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->string('slug');
<<<<<<< HEAD
      $table->string('description')->nullable();
=======
      $table->boolean('is_active')->default(true);
      $table->json('settings')->nullable();
>>>>>>> 822e650043988dd36b016460dba7d17b7acc5591
      $table->timestamps();
      $table->unique(['project_id', 'slug']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('data_types');
  }
};
