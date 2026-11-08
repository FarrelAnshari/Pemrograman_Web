<?php

namespace App\Models;

/**
 * Class User
 * 
 * Merepresentasikan entitas pengguna di FlipPlay.
 * Mengelola koleksi media tontonan (Watchlist) dan bacaan (Reading List).
 */
class User
{
    private string $id;
    private string $name;
    private string $email;
    private string $tier;
    /** @var Media[] */
    private array $watchlist = [];
    /** @var array<string, int> */
    private array $progress = []; // menyimpan progres episode/chapter terakhir

    public function __construct(string $id, string $name, string $email, string $tier = "Premium")
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->tier = $tier;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTier(): string
    {
        return $this->tier;
    }

    /**
     * Menambahkan media ke daftar koleksi pribadi user.
     */
    public function addToWatchlist(Media $media): void
    {
        // Cegah duplikasi
        foreach ($this->watchlist as $item) {
            if ($item->getId() === $media->getId()) {
                return;
            }
        }
        $this->watchlist[] = $media;
        $this->progress[$media->getId()] = 1; // mulai dari episode/bab 1
    }

    /**
     * Memperbarui riwayat tontonan/bacaan.
     */
    public function updateProgress(string $mediaId, int $currentNumber): void
    {
        if (isset($this->progress[$mediaId])) {
            $this->progress[$mediaId] = $currentNumber;
        }
    }

    public function getProgress(string $mediaId): int
    {
        return $this->progress[$mediaId] ?? 0;
    }

    /**
     * @return Media[]
     */
    public function getWatchlist(): array
    {
        return $this->watchlist;
    }
}
