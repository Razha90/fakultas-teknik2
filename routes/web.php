<?php

use App\Http\Controllers\LanguageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Auth;

use App\Livewire\Admin\Departments\DepartmentList;
use App\Livewire\Admin\Category\CategoryList;
use App\Livewire\Admin\Contents\ContentCreate;
use App\Livewire\Admin\Contents\ContentEdit;
use App\Livewire\Admin\Contents\ContentList;
use App\Livewire\Admin\ContentType\ContentTypeList;
use App\Livewire\Admin\Users\UserCreate;
use App\Livewire\Admin\Users\UserIndex;

use App\Livewire\Auth\LoginPage;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Test;
use App\Livewire\Admin\Pages\Page;
use App\Livewire\Admin\Users\UserProfile;

Route::get('lang', [LanguageController::class, 'change'])->name('change.lang');

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');
Volt::route('/', 'home')->name('home');
Volt::route('/news/{id}', 'news-page')->name('news-page');
Volt::route('/search', 'news-search')->name('news-search');

Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
});

Route::post('/create-token', function (Request $request) {
    try {
        $user = $request->user();
        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    } catch (\Throwable $th) {
        Log::error('Error creating token: ' . $th->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
})->name('create.token');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Volt::route('example', 'admin/example-bootstrap')->name('example');
Volt::route('profile', 'profile')->name('profile');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
//     Volt::route('settings/password', 'settings.password')->name('settings.password');
//     Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
// });

Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/dashboard/test', Test::class)->name('dashboard.test');
Route::get('/users/profile', UserProfile::class)->name('users.profile');

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/content', ContentList::class)->name('contents.index');
    Route::get('/content/create', ContentCreate::class)->name('content.create');
    Route::get('/content/{id}/edit', ContentEdit::class)->name('content.edit');
});

Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/categories', CategoryList::class)->name('categories.index');
    Route::get('/departments', DepartmentList::class)->name('departments.index');
    Route::get('/users/create', UserCreate::class)->name('users.create');
    Route::get('/contentType', ContentTypeList::class)->name('contentType.index');
    Route::get('/pages', Page::class)->name(name: 'pages.index');
    Volt::route('/page/{id}', 'edit-page')->name('page-edit');
});
