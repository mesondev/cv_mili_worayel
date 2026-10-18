<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
        });
        Schema::table('galleries', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
            $table->string('alt_text')->nullable();
        });
        Schema::table('partners', function (Blueprint $table) {
            $table->boolean('is_active')->default(false);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
        });
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
        });
        Schema::table('employees', function (Blueprint $table) {
            $table->boolean('is_public')->default(false);
        });
        Schema::table('company_histories', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
        });
        Schema::table('company_visions', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
        });
        Schema::table('company_missions', function (Blueprint $table) {
            $table->boolean('is_published')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('company_missions', fn (Blueprint $table) => $table->dropColumn('is_published'));
        Schema::table('company_visions', fn (Blueprint $table) => $table->dropColumn('is_published'));
        Schema::table('company_histories', fn (Blueprint $table) => $table->dropColumn('is_published'));
        Schema::table('employees', fn (Blueprint $table) => $table->dropColumn('is_public'));
        Schema::table('services', fn (Blueprint $table) => $table->dropColumn('is_published'));
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn('is_published'));
        Schema::table('partners', fn (Blueprint $table) => $table->dropColumn('is_active'));
        Schema::table('galleries', fn (Blueprint $table) => $table->dropColumn(['is_published', 'alt_text']));
        Schema::table('projects', fn (Blueprint $table) => $table->dropColumn(['is_published', 'published_at']));
    }
};
