<?php

namespace App\Models;

/**
 * Class Media (Parent / Superclass)
 * 
 * Kelas induk abstrak yang merepresentasikan entitas media di platform FlipPlay.
 * Menerapkan konsep Encapsulation, Type Hinting, dan Access Modifier.
 */
abstract class Media
{
    // Protected properties agar dapat diwariskan dan diakses oleh subclass (Anime & Manga)
    protected string $id;
    protected string $title;
    protected string $genre;
    protected float $rating;
    protected string $synopsis;
    protected bool $isAvailable;

    /**
     * Constructor untuk inisialisasi awal objek Media.
     */
    public function __construct(
        string $id,
        string $title,
        string $genre,
        float $rating,
        string $synopsis,
        bool $isAvailable = true
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->genre = $genre;
        $this->rating = $rating;
        $this->synopsis = $synopsis;
        $this->isAvailable = $isAvailable;
    }

    // --- GETTER & SETTER (Encapsulation) ---

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getSynopsis(): string
    {
        return $this->synopsis;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function setAvailability(bool $status): void
    {
        $this->isAvailable = $status;
    }

    /**
     * Abstract method yang wajib diimplementasikan oleh subclass dengan perilaku masing-masing (Polymorphism).
     */
    abstract public function getMediaType(): string;
    abstract public function getDetails(): array;
    abstract public function consume(): string;
}
