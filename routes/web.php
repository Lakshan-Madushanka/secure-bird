<?php

declare(strict_types=1);

use App\Http\Controllers\MessagesMediaController;
use App\Http\Livewire\CreateMessageForm;
use App\Http\Livewire\ShowMessage;
use App\Mail\MessageVisitCompleted;
use App\Models\Message;
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
Route::get('/', CreateMessageForm::class)->name('home');

Route::get('about', fn() => view('pages.about'))->name('about');
Route::get('how-it-works', fn() => view('pages.howItWorks'))->name('howItWorks');

Route::middleware(['message.validate'])->prefix('messages')->name('messages.')->group(function (): void {
    Route::get('{messageId}', ShowMessage::class)->name('show');
    Route::get('{messageId}/media-download', [MessagesMediaController::class, 'download'])->name('mediaDownload');
});

//Route::get('test', fn () => dump('test'));
