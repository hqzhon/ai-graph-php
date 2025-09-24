<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LangGraphController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ModelTestController;
use App\Http\Controllers\MultiAgentController;
use App\Http\Controllers\ChatGraphController;
use App\Http\Controllers\WorkflowValidationController;

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

// LangGraph API routes
Route::prefix('langgraph')->group(function () {
    Route::post('/simple-workflow', [LangGraphController::class, 'executeSimpleWorkflow']);
    Route::post('/advanced-workflow', [LangGraphController::class, 'executeAdvancedWorkflow']);
});

// Article workflow routes
Route::prefix('article')->group(function () {
    Route::get('/{id?}', [ArticleController::class, 'demo']);
    Route::post('/{id}/transition/{transition}', [ArticleController::class, 'transition']);
});

// Model testing routes
Route::prefix('model')->group(function () {
    Route::post('/test', [ModelTestController::class, 'testModel']);
});

// Multi-agent workflow routes
Route::prefix('multi-agent')->group(function () {
    Route::post('/stream', [MultiAgentController::class, 'streamWorkflow']);
});

// Chat system routes
Route::prefix('chat')->group(function () {
    Route::post('/process', [ChatGraphController::class, 'processChat']);
    Route::get('/history/{conversationId}', [ChatGraphController::class, 'getConversationHistory']);
});

// Workflow validation routes
Route::prefix('workflow-validation')->group(function () {
    Route::post('/validate', [WorkflowValidationController::class, 'validateWorkflow']);
    Route::get('/report/{validationId}', [WorkflowValidationController::class, 'getValidationReport']);
});
