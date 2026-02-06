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
    Schema::create('data_type_fields', function (Blueprint $table) {
      $table->id();
      $table->foreignId('data_type_id')->constrained()->cascadeOnDelete();
      $table->string('name');
      $table->string('type');
      $table->boolean('required')->default(false);
      $table->boolean('translatable')->default(false);
      $table->json('validation_rules')->nullable();
      $table->integer('sort_order')->default(0);
      $table->string('relation_target')->nullable();
      // slug تبع data_type الهدف (category, product, etc)

      $table->string('relation_type')->nullable();
      // one_to_one | one_to_many | many_to_many (مستقبلاً)

      $table->boolean('multiple')->default(false);
      // هل القيمة array ولا single id
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('data_type_fields');
  }
};
