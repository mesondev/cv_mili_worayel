<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'], 'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:25'], 'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
        ]);
        DB::table('contacts')->insert([...$data, 'status' => 'new', 'created_at' => now(), 'updated_at' => now()]);

        return back()->with('contact_success', 'Terima kasih! Pesan Anda sudah kami terima.');
    }
}
