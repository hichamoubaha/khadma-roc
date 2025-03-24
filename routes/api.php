<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\AnnonceController;

use App\Http\Controllers\CandidatureController;

use App\Http\Controllers\NotificationController;

use App\Http\Controllers\StatistiquesController;

use Illuminate\Support\Facades\Gate;

Route::middleware(['auth:sanctum'])->group(function () {
    // Routes for Admin
    Route::get('/statistiques/admin', function () {
        if (!Gate::allows('is-admin')) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        return response()->json(['message' => 'Admin Statistics Data']);
    });

    // Routes for Recruteur
    Route::get('/statistiques/recruteur', function () {
        if (!Gate::allows('is-recruteur')) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        return response()->json(['message' => 'Recruteur Statistics Data']);
    });
});




Route::middleware(['auth:sanctum', 'role:admin'])->get('/statistiques/admin', [StatistiquesController::class, 'getAdminStatistics']);


Route::middleware('auth:sanctum')->get('/statistiques/recruteur', [StatistiquesController::class, 'statistiquesRecruteur']);
Route::middleware('auth:sanctum')->get('/statistiques/admin', [StatistiquesController::class, 'statistiquesAdmin']);


Route::middleware('auth:sanctum')->put('/candidatures/{id}/statut', [CandidatureController::class, 'updateStatut']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{id}', [NotificationController::class, 'markAsRead']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/candidatures', [CandidatureController::class, 'store']);
    Route::put('/candidatures/{candidature}', [CandidatureController::class, 'update']);
    Route::delete('/candidatures/{candidature}', [CandidatureController::class, 'destroy']);
});

Route::get('/candidatures', [CandidatureController::class, 'index']);
Route::get('/candidatures/{id}', [CandidatureController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/annonces', [AnnonceController::class, 'store']);
    Route::put('/annonces/{annonce}', [AnnonceController::class, 'update']);
    Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy']);
});

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::get('/annonces/{id}', [AnnonceController::class, 'show']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
