<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AccessControlSeeder::class);
    }

    public function test_role_permissions_are_checked_and_can_be_managed_by_super_admin(): void
    {
        $editorRole = Role::where('slug', 'editor')->firstOrFail();
        $superAdmin = User::factory()->create([
            'role_id' => Role::where('slug', 'super-admin')->value('id'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($superAdmin)
            ->get(route('admin.roles.permissions.edit', $editorRole->id))
            ->assertOk()
            ->assertSee('Hak akses: Editor');

        $this->actingAs($superAdmin)
            ->put(route('admin.roles.permissions.update', $editorRole->id), [
                'permissions' => ['berita.view', 'berita.create'],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $editorRole->id,
            'permission_id' => DB::table('permissions')->where('slug', 'berita.create')->value('id'),
        ]);
        $this->assertDatabaseMissing('permission_role', [
            'role_id' => $editorRole->id,
            'permission_id' => DB::table('permissions')->where('slug', 'seo.view')->value('id'),
        ]);
    }

    public function test_editor_is_restricted_to_assigned_modules_and_cannot_update_other_content(): void
    {
        $editor = User::factory()->create([
            'role_id' => Role::where('slug', 'editor')->value('id'),
            'email_verified_at' => now(),
        ]);
        $careerId = DB::table('careers')->insertGetId([
            'title' => 'Site Engineer',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($editor)->get('/admin/berita')->assertOk();
        $this->actingAs($editor)->get('/admin/karir')->assertForbidden();
        $this->actingAs($editor)->delete('/admin/karir/'.$careerId)->assertForbidden();
        $this->actingAs($editor)->get('/admin/pengguna')->assertForbidden();
    }

    public function test_marketing_can_process_inbox_status_and_activity_is_recorded(): void
    {
        $contactId = DB::table('contacts')->insertGetId([
            'name' => 'Klien Uji',
            'email' => 'klien@example.test',
            'subject' => 'Konsultasi',
            'message' => 'Permintaan informasi.',
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $marketing = User::factory()->create([
            'role_id' => Role::where('slug', 'marketing')->value('id'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($marketing)
            ->patch(route('admin.contacts.update', $contactId), ['status' => 'replied'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', ['id' => $contactId, 'status' => 'replied']);
        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => 'contact',
            'subject_id' => $contactId,
            'event' => 'Memperbarui status pesan masuk',
        ]);
    }

    public function test_super_admin_can_create_users_without_exposing_password_hashes(): void
    {
        Notification::fake();
        $superAdmin = User::factory()->create([
            'role_id' => Role::where('slug', 'super-admin')->value('id'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($superAdmin)
            ->post(route('admin.module.store', 'pengguna'), [
                'name' => 'Editor Baru',
                'username' => 'editor-baru',
                'email' => 'editor@example.test',
                'role_id' => Role::where('slug', 'editor')->value('id'),
                'account_status' => 'active',
                'password' => 'StrongPassword123!',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $user = User::where('email', 'editor@example.test')->firstOrFail();
        $this->assertSame('editor-baru', $user->username);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue(Hash::check('StrongPassword123!', $user->password));
        Notification::assertSentTo($user, VerifyEmail::class);

        $this->actingAs($superAdmin)
            ->get(route('admin.module.show', ['pengguna', $user->id]))
            ->assertOk()
            ->assertDontSee($user->password);
    }

    public function test_admin_can_manage_company_history_and_related_categories_cannot_be_deleted(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->value('id'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.module.store', 'sejarah'), [
                'year' => '2020',
                'title' => 'Tonggak perusahaan',
                'description' => 'Informasi sejarah yang telah dikonfirmasi.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $categoryId = DB::table('product_categories')->insertGetId([
            'name' => 'Kategori Uji',
            'slug' => 'kategori-uji',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('products')->insert([
            'product_category_id' => $categoryId,
            'name' => 'Produk Uji',
            'slug' => 'produk-uji',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.module.destroy', ['kategori-produk', $categoryId]))
            ->assertStatus(409);

        $this->assertDatabaseHas('company_histories', ['title' => 'Tonggak perusahaan', 'year' => 2020]);
        $this->assertDatabaseHas('product_categories', ['id' => $categoryId]);
    }

    public function test_editor_cannot_access_visitor_analytics_or_activity_log_without_permission(): void
    {
        $editor = User::factory()->create([
            'role_id' => Role::where('slug', 'editor')->value('id'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($editor)->get(route('admin.analytics.index'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.activity.index'))->assertForbidden();
    }
}
