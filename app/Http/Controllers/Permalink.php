<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Permalink extends Controller
{
    public function formatLink($string)
    {
        try {
            if (filter_var($string, FILTER_VALIDATE_URL)) {
                return $string;
            }
            if (preg_match('/[^\p{L}\p{N}\s_\/-]/u', $string)) {
                return false;
            }

            if (preg_match('#/{2,}#', $string)) {
                return false;
            }
            $string = mb_strtolower($string, 'UTF-8');
            $string = preg_replace('/[\s_]+/', '-', $string);
            $string = preg_replace('/[^a-z0-9\/-]/', '', $string);
            $segments = explode('/', $string);
            $segments = array_map(fn($seg) => trim($seg, '-'), $segments);
            $string = implode('/', $segments);
            if (!str_starts_with($string, '/')) {
                $string = '/' . $string;
            }
            return $string;
        } catch (\Throwable $th) {
            Log::error('Error formatting link: ' . $th->getMessage());
            return 'error-formatting-link';
        }
    }

    public function checkLink($link)
    {
        try {
            if (empty($link)) {
                return false;
            }
            $existing = Page::where('path', $link)->first();
            if ($existing) {
                return false;
            }
            $menuExiting = Menu::where('path', $link)->first();
            if ($menuExiting) {
                return false;
            }
            return true;
        } catch (\Throwable $th) {
            Log::error('Error checking link: ' . $th->getMessage());
            return false;
        }
    }
}
