<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\News;
use App\Models\NewsTranslations;
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
            $page = Page::find($id);
            $jsonContent = json_encode($request->all(), JSON_PRETTY_PRINT);
            if ($page && $page->data) {
                $path = $page->data;
                if (!Storage::exists($path)) {
                    $path = 'grapesjs/' . Str::random(20) . '.json';
                }
            } else {
                $path = 'grapesjs/' . Str::random(20) . '.json';
            }
            Storage::put($path, $jsonContent);
            Page::updateOrCreate(['id' => $id], ['data' => $path]);
            return response()->json(['message' => 'Data berhasil disimpan']);
        } catch (\Throwable $th) {
            Log::error('Error saving data: ' . $th->getMessage());
        }
    }

    public function getPage($id)
    {
        try {
            $page = Page::find($id);
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

    public function saveCompile(Request $request, $id)
    {
        try {
            $page = Page::find($id);
            $htmlContent = $request->input('html');
            $cssContent = $request->input('css');
            $compiledHtml = "<!DOCTYPE html>\n<html>\n<head>\n<style>\n" . $cssContent . "\n</style>\n</head>\n<body>\n" . $htmlContent . "\n</body>\n</html>";
            if ($page && $page->html) {
                $oldPath = $page->html;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $oldPath;
            } else {
                $path = 'grapesjs_html/' . Str::random(20) . '.html';
            }
            Storage::disk('public')->put($path, $compiledHtml);
            Page::updateOrCreate(['id' => $id], ['html' => $path]);
            return response()->json(['message' => 'HTML berhasil disimpan']);
            // $page = Page::find($id);

            // $htmlContent = $request->input('html');
            // $cssContent = $request->input('css');

            // $compiledHtml = "<!DOCTYPE html>\n<html>\n<head>\n<style>\n" . $cssContent . "\n</style>\n</head>\n<body>\n" . $htmlContent . "\n</body>\n</html>";

            // $path = 'grapesjs_html/' . Str::random(20) . '.html';

            // // Hapus file lama jika ada
            // if ($page && $page->html) {
            //     $oldUrl = $page->html;

            //     // Ambil path dari URL
            //     $oldPath = str_replace(Storage::disk('public')->url(''), '', $oldUrl);

            //     if (Storage::disk('public')->exists($oldPath)) {
            //         Storage::disk('public')->delete($oldPath);
            //     }
            // }

            // // Simpan file baru
            // Storage::disk('public')->put($path, $compiledHtml);

            // // Simpan URL lengkap ke database
            // $url = Storage::disk('public')->url($path);

            // Page::updateOrCreate(['id' => $id], ['html' => $url]);

            // return response()->json(['message' => 'HTML berhasil disimpan']);
        } catch (\Throwable $th) {
            Log::error('Error loading data: ' . $th->getMessage());
            return response()->json(['message' => 'Error loading data'], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            $file = $request->file('file');
            $jenisFile = $request->header('Jenis-File');
            $pageId = $request->header('page-Id');

            if (is_array($file) && count($file) > 0) {
                $file = $file[0];
            }

            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $path = $file->store('images', 'public');
                $url = Storage::url($path);

                $namaFile = $file->getClientOriginalName();
                if (empty($namaFile)) {
                    $extension = $file->getClientOriginalExtension();
                    $namaFile = Str::random(20) . '.' . $extension;
                }
                if ($jenisFile === 'page') {
                    File::create([
                        'name' => $namaFile,
                        'path' => $path,
                        'type' => $jenisFile,
                        'page_id' => $pageId,
                    ]);
                } else {
                    File::create([
                        'name' => $namaFile,
                        'path' => $path,
                        'type' => $jenisFile,
                        'news_id' => $pageId,
                    ]);
                }

                return response()->json([
                    'data' => [$url],
                ]);
            }

            return response()->json([
                'data' => [],
            ]);
        } catch (\Throwable $th) {
            Log::error('Error uploading image: ' . $th->getMessage());
            return response()->json([
                'data' => [],
            ]);
        }
    }

    public function saveNews(Request $request, $id)
    {
        try {
            $locale = $request->header('locale');
            $page = News::with('translations')->find($id);
            $jsonContent = json_encode($request->all(), JSON_PRETTY_PRINT);
            if (!$page) {
                return response()->json(['message' => 'News not found.'], 404);
            }

            $translation = $page->translations->firstWhere('locale', $locale);

            if ($translation && $translation->content) {
                $path = $translation->content;
                if (!Storage::exists($path)) {
                    $path = 'grapesjs/' . Str::random(20) . '.json';
                }
            } else {
                $path = 'grapesjs/' . Str::random(20) . '.json';
            }
            Storage::put($path, $jsonContent);
            NewsTranslations::updateOrCreate(
                ['news_id' => $id, 'locale' => $locale],
                ['content' => $path]
            );
            // $translation->updateOrCreate(['locale' => $locale], ['content' => $path]);

            return response()->json(['message' => 'Data berhasil disimpan']);
        } catch (\Throwable $th) {
            Log::error('Error saving data: ' . $th->getMessage());
        }
    }

    public function getNews(Request $request ,$id)
    {
        try {
            $locale = $request->header('locale');

            $page = News::with('translations')->find($id);
            if (!$page) {
                return response()->json(['message' => 'News not found.'], 404);
            }

            $translation = $page->translations->firstWhere('locale', $locale);

            if (!$translation || !$translation->content) {
                return response()->json(['message' => 'No translation found.'], 404);

                // $fallback = $page->translations->first();
                // if ($fallback->content) {
                //     $translation = $page->translations()->create([
                //         'locale' => $locale,
                //         'title' => $fallback->title,
                //         'content' => $fallback->content,
                //         'html' => $fallback->html,
                //     ]);
                // } else {
                //     return response()->json(['message' => 'No translation found.'], 404);
                // }
            }

            if (!Storage::exists($translation->content)) {
                return response()->json(['message' => 'File data tidak ditemukan'], 404);
            }
            $jsonContent = Storage::get($translation->content);
            return response()->json(json_decode($jsonContent, true));
        } catch (\Throwable $th) {
            Log::error('Error GetNews data: ' . $th->getMessage());
            return response()->json(['message' => 'Error loading data'], 500);
        }
    }

    public function compileNews(Request $request, $id)
    {
        try {
            $locale = $request->header('locale');

            $page = News::with('translations')->find($id);

            if (!$page) {
                return response()->json(['message' => 'News not found'], 404);
            }
            $htmlContent = $request->input('html');
            $cssContent = $request->input('css');
            $compiledHtml = "<!DOCTYPE html>\n<html>\n<head>\n<style>\n" . $cssContent . "\n</style>\n</head>\n<body>\n" . $htmlContent . "\n</body>\n</html>";

            $translation = $page->translations()->where('locale', $locale)->first();
            if ($translation && $translation->html) {
                $oldPath = $translation->html;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $oldPath;
            } else {
                $path = 'grapesjs_html/' . Str::random(20) . '.html';
            }
            Storage::disk('public')->put($path, $compiledHtml);
            NewsTranslations::updateOrCreate(
                ['news_id' => $id, 'locale' => $locale],
                ['html' => $path]
            );
            // $translation->updateOrCreate(['locale' => $locale], ['html' => $path]);
            // Page::updateOrCreate(['id' => $id], ['html' => $path]);
            return response()->json(['message' => 'HTML berhasil disimpan']);
        } catch (\Throwable $th) {
            Log::error('Error Compile News: ' . $th->getMessage());
            return response()->json(['message' => 'Error loading data'], 500);
        }
    }
}
