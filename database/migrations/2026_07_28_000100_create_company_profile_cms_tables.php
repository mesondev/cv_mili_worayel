<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->timestamps();
        });
        Schema::create('permissions', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->timestamps();
        });
        Schema::create('permission_role', function (Blueprint $t) {
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $t->primary(['role_id', 'permission_id']);
        });
        Schema::table('users', function (Blueprint $t) {
            $t->string('phone', 25)->nullable()->after('email');
            $t->text('address')->nullable()->after('phone');
            $t->foreignId('role_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
        Schema::create('company_profiles', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('tagline')->nullable();
            $t->longText('description')->nullable();
            $t->string('logo')->nullable();
            $t->string('email')->nullable();
            $t->string('phone')->nullable();
            $t->text('address')->nullable();
            $t->string('whatsapp')->nullable();
            $t->string('video_url')->nullable();
            $t->timestamps();
        });
        Schema::create('company_histories', function (Blueprint $t) {
            $t->id();
            $t->year('year')->nullable();
            $t->string('title');
            $t->text('description')->nullable();
            $t->timestamps();
        });
        foreach (['company_visions', 'company_missions'] as $table) {
            Schema::create($table, function (Blueprint $t) {
                $t->id();
                $t->text('content');
                $t->timestamps();
            });
        }
        Schema::create('product_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('description')->nullable();
            $t->decimal('price', 15, 2)->nullable();
            $t->string('image')->nullable();
            $t->string('catalog_pdf')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('icon')->nullable();
            $t->text('excerpt')->nullable();
            $t->longText('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('project_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->timestamps();
        });
        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->foreignId('project_category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('client_name')->nullable();
            $t->longText('description')->nullable();
            $t->longText('case_study')->nullable();
            $t->string('cover_image')->nullable();
            $t->date('completed_at')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->timestamps();
        });
        Schema::create('gallery_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->timestamps();
        });
        Schema::create('galleries', function (Blueprint $t) {
            $t->id();
            $t->foreignId('gallery_category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->enum('type', ['image', 'video'])->default('image');
            $t->string('file_path')->nullable();
            $t->string('video_url')->nullable();
            $t->text('caption')->nullable();
            $t->timestamps();
        });
        Schema::create('news_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->timestamps();
        });
        Schema::create('news', function (Blueprint $t) {
            $t->id();
            $t->foreignId('news_category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('content');
            $t->string('cover_image')->nullable();
            $t->timestamp('published_at')->nullable();
            $t->boolean('is_published')->default(false);
            $t->timestamps();
        });
        Schema::create('careers', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('department')->nullable();
            $t->string('employment_type')->nullable();
            $t->string('location')->nullable();
            $t->longText('description')->nullable();
            $t->longText('requirements')->nullable();
            $t->date('expires_at')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('job_applications', function (Blueprint $t) {
            $t->id();
            $t->foreignId('career_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('email');
            $t->string('phone')->nullable();
            $t->string('cv_path')->nullable();
            $t->text('cover_letter')->nullable();
            $t->enum('status', ['new', 'reviewed', 'interview', 'rejected', 'accepted'])->default('new');
            $t->timestamps();
        });
        Schema::create('testimonials', function (Blueprint $t) {
            $t->id();
            $t->string('client_name');
            $t->string('position')->nullable();
            $t->string('company')->nullable();
            $t->tinyInteger('rating')->default(5);
            $t->text('content');
            $t->string('video_url')->nullable();
            $t->string('photo')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('partners', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('type')->default('partner');
            $t->string('logo')->nullable();
            $t->string('website')->nullable();
            $t->string('certificate')->nullable();
            $t->timestamps();
        });
        Schema::create('contacts', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->string('phone')->nullable();
            $t->string('subject')->nullable();
            $t->text('message');
            $t->enum('status', ['new', 'read', 'replied', 'archived'])->default('new');
            $t->timestamps();
        });
        Schema::create('visitor_logs', function (Blueprint $t) {
            $t->id();
            $t->string('ip_address', 45)->nullable();
            $t->string('path')->nullable();
            $t->string('referrer')->nullable();
            $t->string('user_agent')->nullable();
            $t->timestamp('visited_at')->useCurrent();
        });
        Schema::create('activity_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('event');
            $t->string('subject_type')->nullable();
            $t->unsignedBigInteger('subject_id')->nullable();
            $t->text('properties')->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->timestamps();
        });
        Schema::create('seo_settings', function (Blueprint $t) {
            $t->id();
            $t->string('page')->unique();
            $t->string('meta_title')->nullable();
            $t->text('meta_description')->nullable();
            $t->string('keywords')->nullable();
            $t->string('og_image')->nullable();
            $t->timestamps();
        });
        Schema::create('social_media', function (Blueprint $t) {
            $t->id();
            $t->string('platform');
            $t->string('url');
            $t->string('icon')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->string('group')->default('general');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['settings', 'social_media', 'seo_settings', 'activity_logs', 'visitor_logs', 'contacts', 'partners', 'testimonials', 'job_applications', 'careers', 'news', 'news_categories', 'galleries', 'gallery_categories', 'projects', 'project_categories', 'services', 'products', 'product_categories', 'company_missions', 'company_visions', 'company_histories', 'company_profiles', 'permission_role', 'permissions', 'roles'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', function (Blueprint $t) {
            $t->dropConstrainedForeignId('role_id');
            $t->dropColumn(['phone', 'address']);
        });
    }
};
