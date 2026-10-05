<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::create('channels', function (Blueprint $table) { $table->id(); $table->foreignId('community_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->string('slug'); $table->text('description')->nullable(); $table->string('kind', 20)->default('text'); $table->boolean('is_private')->default(false); $table->timestamps(); $table->unique(['community_id', 'slug']); }); }
    public function down(): void { Schema::dropIfExists('channels'); }
};
