<?php

namespace App\Models;

/**
 * Class Anime (Subclass / Child Class)
 * 
 * Mewarisi sifat dari class Media (Inheritance)
 * Menambahkan atribut dan perilaku spesifik untuk video streaming anime.
 */
class Anime extends Media
{
    private int $totalEpisodes;
    private string $studio;
    private string $resolution;
    private bool $hasSubIndo;

    public function __construct(
        string $id,
        string $title,
        string $genre,
        float $rating,
        string $synopsis,
        int $totalEpisodes,
        string $studio,
        string $resolution = "1080p Full HD",
        bool $hasSubIndo = true,
        bool $isAvailable = true
    ) {
        // Memanggil constructor parent class
        parent::__construct($id, $title, $genre, $rating, $synopsis, $isAvailable);
        
        $this->totalEpisodes = $totalEpisodes;
        $this->studio = $studio;
        $this->resolution = $resolution;
        $this->hasSubIndo = $hasSubIndo;
    }

    public function getTotalEpisodes(): int
    {
        return $this->totalEpisodes;
    }

    public function getStudio(): string
    {
        return $this->studio;
    }

    public function getResolution(): string
    {
        return $this->resolution;
    }

    public function hasSubIndo(): bool
    {
        return $this->hasSubIndo;
    }

    public function getMediaType(): string
    {
        return "Anime (Streaming)";
    }

    public function getDetails(): array
    {
        return [
            "ID" => $this->id,
            "Judul" => $this->title,
            "Tipe" => $this->getMediaType(),
            "Genre" => $this->genre,
            "Rating" => "★ " . number_format($this->rating, 2),
            "Studio" => $this->studio,
            "Total Episode" => $this->totalEpisodes . " Episode",
            "Kualitas" => $this->resolution,
            "Subtitle" => $this->hasSubIndo ? "Indonesia (Resmi)" : "English",
            "Status" => $this->isAvailable ? "Siap Ditonton" : "Segera Hadir",
            "Sinopsis" => $this->synopsis
        ];
    }

    public function consume(): string
    {
        return "▶ Streaming: Sedang memutar '{$this->title}' [Episode 1/{$this->totalEpisodes}] kualitas {$this->resolution} dengan Subtitle Indonesia.";
    }

    public function playEpisode(int $episodeNumber): string
    {
        if ($episodeNumber < 1 || $episodeNumber > $this->totalEpisodes) {
            return "Episode {$episodeNumber} tidak valid! Judul ini hanya memiliki {$this->totalEpisodes} episode.";
        }
        return "▶ Streaming: Sedang memutar '{$this->title}' - Episode {$episodeNumber}/{$this->totalEpisodes} ({$this->resolution}).";
    }
}
