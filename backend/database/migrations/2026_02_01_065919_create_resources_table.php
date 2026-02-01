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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('isbn')->nullable()->unique();
            $table->enum('resource_type', ['book', 'journal', 'magazine', 'dvd', 'cd', 'research_paper', 'ebook', 'audiobook'])->default('book');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('publisher_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('publication_year')->nullable();
            $table->string('language')->default('en');
            $table->integer('pages')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable()->comment('For digital resources like e-books');
            $table->enum('status', ['available', 'unavailable', 'archived'])->default('available');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('author_resource', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['author', 'co_author', 'editor', 'translator'])->default('author');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_resource');
        Schema::dropIfExists('resources');
    }
};
