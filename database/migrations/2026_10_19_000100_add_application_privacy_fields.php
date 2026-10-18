<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('reference_code', 40)->nullable()->unique();
            $table->timestamp('privacy_consent_at')->nullable();
            $table->text('internal_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropUnique(['reference_code']);
            $table->dropColumn(['reference_code', 'privacy_consent_at', 'internal_notes']);
        });
    }
};
