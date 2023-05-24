<?php

declare(strict_types=1);

use App\Http\Controllers\MessagesController;
use App\Http\Livewire\MessageForm;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', MessageForm::class)->name('home');

Route::middleware(['message.validate'])->prefix('messages')->name('messages.')->group(function (): void {
    Route::get('{messageId}', [MessagesController::class, 'show'])->name('show');
});
