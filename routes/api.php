<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SpaceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Authentication
Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
}); 

// Client
Route::controller(ClientController::class)->group(function () {
    Route::get('/c/me', 'getClient');
    Route::post('/c/me', 'createClient');
}); 

// Tenant
Route::post('/t/me', 
    [TenantController::class, 'createTenant']
);

Route::get('/t/me', 
    [TenantController::class, 'me']
);

// Space
Route::post('/space', 
    [SpaceController::class, 'create']
);
Route::get('/space', 
    [SpaceController::class, 'getAll']
);

// Admin
Route::get('/a/accounts/tenants', 
    [AdminController::class, 'getAllTenants']
);

Route::get('/a/accounts/clients', 
    [AdminController::class, 'getAllClients']
);

Route::put('/a/accounts/tenants/verify/{id}', 
    [AdminController::class, 'verifyTenant']
);



// Stripe Subscription
Route::get('/stripe/subscription/{id}', 
    [SubscriptionController::class, 'getSubscription']
);
Route::get('/stripe/subscription/{product_id}/{price_id}/payment-link', 
    [SubscriptionController::class, 'generatePaymentLink']
);
Route::get('/stripe/subscription/{paymentLinkId}/payment-link/status', 
    [SubscriptionController::class, 'checkPaymentLinkStatus']
);
Route::post('/stripe/subscribe/{id}', 
    [SubscriptionController::class, 'createSubscription']
);
Route::get('/stripe/payment/{payment_intent_id}/status', 
    [SubscriptionController::class, 'checkPaymentStatus']
);
Route::post('/stripe/payment/one-time', 
    [SubscriptionController::class, 'createOneTimePayment']
);
Route::post('/stripe/payment/checkout-session', 
    [SubscriptionController::class, 'createCheckoutSession']
);