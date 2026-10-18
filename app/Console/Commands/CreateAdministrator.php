<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdministrator extends Command
{
    protected $signature = 'app:admin-create';

    protected $description = 'Create a verified Super Admin using private terminal prompts';

    public function handle(): int
    {
        if (! Role::where('slug', 'super-admin')->exists()) {
            $this->call('db:seed', ['--class' => AccessControlSeeder::class]);
        }

        $name = trim((string) $this->ask('Nama administrator'));
        $email = strtolower(trim((string) $this->ask('Email administrator')));
        $username = strtolower(trim((string) $this->ask('Username administrator')));
        $password = $this->secret(
            'Password (minimal 12 karakter, huruf besar/kecil, angka, simbol)'
        );

        if (! is_string($password) || $password === '') {
            $this->error('Password tidak terbaca. Silakan jalankan ulang perintah.');

            return self::FAILURE;
        }

        $confirmation = $this->secret('Ulangi password');

        if (! is_string($confirmation) || $confirmation === '') {
            $this->error('Konfirmasi password tidak terbaca. Silakan jalankan ulang perintah.');

            return self::FAILURE;
        }

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'password_confirmation' => $confirmation,
        ], [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'alpha_dash', 'min:3', 'max:30', 'unique:users,username'],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $role = Role::where('slug', 'super-admin')->firstOrFail();
        User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'account_status' => 'active',
            'password_changed_at' => now(),
            'role_id' => $role->id,
        ]);

        $this->info('Administrator berhasil dibuat. Kredensial tidak ditampilkan kembali.');

        return self::SUCCESS;
    }
}
