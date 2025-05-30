<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function change(Request $request)
    {
        Log::info('Language Session', [
            'lang' => $request->lang,
            'cookie' => $request->cookie('locale'),
        ]);
        $locale = $request->lang;
        try {
            if (auth()->check()) {
                $user = auth()->user(); 
                if ($user->language !== $locale) {
                    $user->update(['language' => $locale]);
                }
            }
        } catch (\Throwable $th) {
            Log::error('Language Session'. $th);
        }

        return redirect()->back()->withCookie(cookie('locale', $locale, 43200));
    }
}
