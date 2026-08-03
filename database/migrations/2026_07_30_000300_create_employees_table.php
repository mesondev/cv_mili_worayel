<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 25)->nullable();
            $table->string('position');
            $table->string('department')->nullable();
            $table->date('joined_at')->nullable();
            $table->enum('employment_status', ['active', 'contract', 'inactive'])->default('active');
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
