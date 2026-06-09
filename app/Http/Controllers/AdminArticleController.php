<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminArticleController extends Controller
{
    /**
     * Tampilkan Semua Artikel (Termasuk softdeleted jika requested)
     */
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $articles = $query->with(['category', 'author:id,name'])->get();

        return response()->json([
            'status' => 200,
            'message' => 'Articles retrieved successfully',
            'data' => $articles
        ], 200);
    }

    /**
     * Tambah Artikel Baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $imagePath = '/storage/' . $imagePath;
        }

        // slug & author_id ditangani (slug via Str::slug, author_id via ArticleObserver)
        $article = Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5), // slug unik
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image_path' => $imagePath,
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Article created successfully',
            'data' => $article->load(['category', 'author:id,name'])
        ], 201);
    }

    /**
     * Tampilkan Detail Artikel
     */
    public function show($id)
    {
        $article = Article::withTrashed()->with(['category', 'author:id,name'])->find($id);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Article retrieved successfully',
            'data' => $article
        ], 200);
    }

    /**
     * Update Artikel
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($article->image_path) {
                $oldPath = str_replace('/storage/', '', $article->image_path);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $request->file('image')->store('articles', 'public');
            $article->image_path = '/storage/' . $imagePath;
        }

        $article->title = $request->title;
        if ($article->isDirty('title')) {
            $article->slug = Str::slug($request->title) . '-' . Str::random(5);
        }
        $article->content = $request->content;
        $article->category_id = $request->category_id;
        
        // author_id otomatis di-update oleh ArticleObserver saat save
        $article->save();

        return response()->json([
            'status' => 200,
            'message' => 'Article updated successfully',
            'data' => $article->load(['category', 'author:id,name'])
        ], 200);
    }

    /**
     * Hapus Artikel (Soft Delete)
     */
    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found or already deleted',
                'data' => null
            ], 404);
        }

        $article->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Article soft deleted successfully',
            'data' => null
        ], 200);
    }

    /**
     * Restore Artikel yang dihapus
     */
    public function restore($id)
    {
        $article = Article::onlyTrashed()->find($id);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Deleted article not found',
                'data' => null
            ], 404);
        }

        $article->restore();

        return response()->json([
            'status' => 200,
            'message' => 'Article restored successfully',
            'data' => $article->load(['category', 'author:id,name'])
        ], 200);
    }
}
