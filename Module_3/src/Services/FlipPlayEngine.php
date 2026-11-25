<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Anime;
use App\Models\Manga;

/**
 * Class FlipPlayEngine
 * 
 * Service utama yang mengelola pustaka media, operasi pencarian,
 * serta filtering katalog di platform FlipPlay.
 */
class FlipPlayEngine
{
    /** @var Media[] */
    private array $mediaLibrary = [];
    private string $platformName;
    private string $version;

    public function __construct(string $platformName = "FlipPlay Engine", string $version = "v3.0-OOP")
    {
        $this->platformName = $platformName;
        $this->version = $version;
    }

    public function getPlatformInfo(): string
    {
        return "{$this->platformName} ({$this->version})";
    }

    /**
     * Menambahkan media baru ke dalam library.
     */
    public function addMedia(Media $media): void
    {
        $this->mediaLibrary[$media->getId()] = $media;
    }

    /**
     * Mengambil seluruh daftar media yang tersedia.
     * @return Media[]
     */
    public function getAllMedia(): array
    {
        return array_values($this->mediaLibrary);
    }

    /**
     * Mencari media berdasarkan kata kunci judul.
     * @return Media[]
     */
    public function searchByTitle(string $keyword): array
    {
        $results = [];
        $keyword = strtolower(trim($keyword));

        foreach ($this->mediaLibrary as $media) {
            if (str_contains(strtolower($media->getTitle()), $keyword)) {
                $results[] = $media;
            }
        }

        return $results;
    }

    /**
     * Mengambil detail media berdasarkan ID.
     */
    public function findById(string $id): ?Media
    {
        return $this->mediaLibrary[$id] ?? null;
    }

    /**
     * Menghitung total media yang terdaftar.
     */
    public function getTotalMediaCount(): int
    {
        return count($this->mediaLibrary);
    }
}

