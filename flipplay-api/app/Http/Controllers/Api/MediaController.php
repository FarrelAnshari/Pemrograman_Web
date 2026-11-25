<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MediaController extends Controller
{
    /**
     * Display a listing of media with optional title/search and type filters.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Media::query();

        $keyword = $request->query('title', $request->query('search'));
        if (is_string($keyword) && trim($keyword) !== '') {
            $query->where('title', 'LIKE', '%' . trim($keyword) . '%');
        }

        $type = $request->query('type');
        if (in_array($type, ['anime', 'manga'], true)) {
            $query->where('type', $type);
        }

        return MediaResource::collection($query->orderBy('id')->get());
    }

    /**
     * Store a newly created media item.
     */
    public function store(StoreMediaRequest $request): JsonResponse
    {
        $media = Media::create($request->validated());

        return (new MediaResource($media))
            ->additional(['message' => 'Media berhasil ditambahkan ke katalog FlipPlay.'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified media item along with collector users.
     */
    public function show(Media $media): MediaResource
    {
        $media->load('users');

        return new MediaResource($media);
    }

    /**
     * Update the specified media item.
     */
    public function update(UpdateMediaRequest $request, Media $media): MediaResource
    {
        $media->update($request->validated());

        return (new MediaResource($media->fresh()))
            ->additional(['message' => 'Data media berhasil diperbarui.']);
    }

    /**
     * Remove the specified media item.
     */
    public function destroy(Media $media): JsonResponse
    {
        $title = $media->title;
        $media->delete();

        return response()->json([
            'message' => "Media '{$title}' berhasil dihapus dari katalog.",
        ]);
    }
}
