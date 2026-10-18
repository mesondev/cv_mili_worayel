<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PublicNewsVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_unpublished_and_scheduled_articles_are_not_publicly_accessible(): void
    {
        DB::table('news')->insert([
            ['title' => 'Draft', 'slug' => 'draft', 'content' => 'Private draft', 'is_published' => false, 'published_at' => now()->subDay(), 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Draft fallback collision', 'slug' => 'komitmen-kualitas-dalam-setiap-tahapan-pembangunan', 'content' => 'Private draft with a fallback slug', 'is_published' => false, 'published_at' => now()->subDay(), 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Scheduled', 'slug' => 'scheduled', 'content' => 'Future article', 'is_published' => true, 'published_at' => now()->addDay(), 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->get(route('news.show', 'draft'))->assertNotFound();
        $this->get(route('news.show', 'scheduled'))->assertNotFound();
        $this->get(route('news.show', 'komitmen-kualitas-dalam-setiap-tahapan-pembangunan'))->assertNotFound();
    }

    public function test_published_article_is_visible_on_home_and_detail_page(): void
    {
        DB::table('news')->insert([
            'title' => 'Berita Terbit',
            'slug' => 'berita-terbit',
            'content' => 'Isi artikel yang dapat dibaca.',
            'is_published' => true,
            'published_at' => now()->subMinute(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Berita Terbit');
        $this->get(route('news.show', 'berita-terbit'))->assertOk()->assertSee('Isi artikel yang dapat dibaca.');
    }
}
