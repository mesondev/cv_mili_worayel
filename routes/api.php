<?php

use App\Http\Controllers\Api\CompanyProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/company-profile', [CompanyProfileController::class, 'show']);
