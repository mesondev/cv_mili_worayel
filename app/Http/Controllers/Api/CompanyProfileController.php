<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CompanyProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $profile = DB::getSchemaBuilder()->hasTable('company_profiles')
            ? DB::table('company_profiles')->latest('id')->first()
            : null;

        return response()->json([
            'name' => $profile?->name ?? 'CV MILI WORAYEL MAJU',
            'tagline' => $profile?->tagline ?? 'Membangun nilai yang bertahan.',
            'description' => $profile?->description,
            'email' => $profile?->email,
            'phone' => $profile?->phone,
            'address' => $profile?->address,
            'whatsapp' => $profile?->whatsapp,
            'video_url' => $profile?->video_url,
        ]);
    }
}
