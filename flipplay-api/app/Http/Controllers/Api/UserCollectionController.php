<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCollectionRequest;
use App\Http\Requests\UpdateProgressRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserCollectionController extends Controller
{
    /**
     * Display the user's media collection with watch/read progress.
     */
    public function index(User $user): UserResource
    {
        $user->load('media');

        return new UserResource($user);
    }

    /**
     * Attach a media item to the user's collection (reject duplicates with HTTP 409).
     */
    public function store(AddToCollectionRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();
        $mediaId = (int) $validated['media_id'];

        if ($user->media()->where('media_id', $mediaId)->exists()) {
            return response()->json([
                'message' => 'Media sudah ada di koleksi pengguna.',
            ], 409);
        }

        $user->media()->attach($mediaId, [
            'progress' => $validated['progress'] ?? 1,
        ]);

        $user->load('media');

        return (new UserResource($user))
            ->additional(['message' => 'Media berhasil ditambahkan ke koleksi pengguna.'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update watch/read progress for a media item in the user's collection.
     */
    public function update(UpdateProgressRequest $request, User $user, Media $media): JsonResponse|MediaResource
    {
        if (! $user->media()->where('media_id', $media->id)->exists()) {
            return response()->json([
                'message' => 'Media tidak ditemukan di koleksi pengguna.',
            ], 404);
        }

        $user->media()->updateExistingPivot($media->id, [
            'progress' => $request->validated()['progress'],
        ]);

        $updatedMedia = $user->media()->findOrFail($media->id);

        return (new MediaResource($updatedMedia))
            ->additional(['message' => 'Progres media berhasil diperbarui.']);
    }

    /**
     * Detach a media item from the user's collection.
     */
    public function destroy(User $user, Media $media): JsonResponse
    {
        if (! $user->media()->where('media_id', $media->id)->exists()) {
            return response()->json([
                'message' => 'Media tidak ditemukan di koleksi pengguna.',
            ], 404);
        }

        $user->media()->detach($media->id);

        return response()->json([
            'message' => "Media '{$media->title}' berhasil dihapus dari koleksi pengguna.",
        ]);
    }
}
