<?php

namespace App\Models;

/**
 * Class Manga (Subclass / Child Class)
 * 
 * Mewarisi sifat dari class Media (Inheritance)
 * Menambahkan atribut dan perilaku spesifik untuk pembaca manga/komik digital.
 */
class Manga extends Media
{
    private int $totalChapters;
    private string $author;
    private bool $isColor;
    private bool $isCompleted;

    public function __construct(
        string $id,
        string $title,
        string $genre,
        float $rating,
        string $synopsis,
        int $totalChapters,
        string $author,
        bool $isColor = false,
        bool $isCompleted = false,
        bool $isAvailable = true
    ) {
        // Memanggil constructor parent class
        parent::__construct($id, $title, $genre, $rating, $synopsis, $isAvailable);
        
        $this->totalChapters = $totalChapters;
        $this->author = $author;
        $this->isColor = $isColor;
        $this->isCompleted = $isCompleted;
    }

    public function getTotalChapters(): int
    {
        return $this->totalChapters;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function isColor(): bool
    {
        return $this->isColor;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function getMediaType(): string
    {
        return "Manga (Digital Reader)";
    }

    public function getDetails(): array
    {
        return [
            "ID" => $this->id,
            "Judul" => $this->title,
            "Tipe" => $this->getMediaType(),
            "Genre" => $this->genre,
            "Rating" => "★ " . number_format($this->rating, 2),
            "Penulis / Ilustrator" => $this->author,
            "Total Chapter" => $this->totalChapters . " Bab",
            "Format Halaman" => $this->isColor ? "Full Color (Manhwa/Webtoon)" : "Monochrome B&W (Manga)",
            "Status Rilis" => $this->isCompleted ? "Tamat" : "Sedang Berjalan (Ongoing)",
            "Status" => $this->isAvailable ? "Siap Dibaca" : "Segera Hadir",
            "Sinopsis" => $this->synopsis
        ];
    }

    public function consume(): string
    {
        return "📖 Membaca: Membuka reader vertikal '{$this->title}' [Bab 1] oleh {$this->author}.";
    }

    public function readChapter(int $chapterNumber): string
    {
        if ($chapterNumber < 1 || $chapterNumber > $this->totalChapters) {
            return "Bab {$chapterNumber} tidak ditemukan! Manga ini baru memiliki {$this->totalChapters} bab.";
        }
        return "📖 Membaca: '{$this->title}' - Bab {$chapterNumber}/{$this->totalChapters}. Tampilan baca optimal aktif.";
    }
}
