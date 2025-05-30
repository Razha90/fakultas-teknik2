<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Editor extends Controller
{
    public function saveData(Request $request, $id)
    {
        try {
            $user = $request->user();
            $jsonContent = json_encode($request->all(), JSON_PRETTY_PRINT);
            $path = 'grapesjs/' . Str::random(20) . '.json';
            Storage::put($path, $jsonContent);
            Page::updateOrCreate(['user_id' => $user->id], ['data' => $path]);
            return response()->json(['message' => 'Data berhasil disimpan']);
        } catch (\Throwable $th) {
            Log::error('Error saving data: ' . $th->getMessage());
        }
    }

    public function getPage(Request $request, $id)
    {
        try {
            $user = $request->user();
            $page = Page::where('user_id', $user->id)->first();
            if (!$page || !$page->data) {
                return response()->json(['message' => 'Data halaman tidak ditemukan'], 404);
            }
            if (!Storage::exists($page->data)) {
                return response()->json(['message' => 'File data tidak ditemukan'], 404);
            }
            $jsonContent = Storage::get($page->data);
            return response()->json(json_decode($jsonContent, true));
        } catch (\Throwable $th) {
            Log::error('Error loading data: ' . $th->getMessage());
            return response()->json(['message' => 'Error loading data'], 500);
        }
    }
}
