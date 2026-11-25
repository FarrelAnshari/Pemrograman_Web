<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial FlipPlay data from Module_3.
     */
    public function run(): void
    {
        $blackClover = Media::create([
            'code' => 'ANM-001',
            'title' => 'Black Clover: Sword of the Wizard King',
            'type' => 'anime',
            'genre' => 'Aksi, Fantasi, Shonen',
            'rating' => 8.15,
            'synopsis' => 'Asta yang terlahir tanpa sihir tetap bertekad membuktikan mimpinya menjadi Kaisar Sihir.',
            'is_available' => true,
            'total_episodes' => 170,
            'studio' => 'Studio Pierrot',
            'resolution' => '1080p Full HD',
            'has_sub_indo' => true,
        ]);

        $frieren = Media::create([
            'code' => 'ANM-002',
            'title' => "Sousou no Frieren (Beyond Journey's End)",
            'type' => 'anime',
            'genre' => 'Petualangan, Drama, Fantasi',
            'rating' => 9.24,
            'synopsis' => 'Setelah kekalahan Raja Iblis, sang penyihir elf Frieren memulai perjalanan baru memahami manusia.',
            'is_available' => true,
            'total_episodes' => 28,
            'studio' => 'Madhouse',
            'resolution' => '4K Ultra HD',
            'has_sub_indo' => true,
        ]);

        Media::create([
            'code' => 'ANM-003',
            'title' => 'Solo Leveling: Arise',
            'type' => 'anime',
            'genre' => 'Aksi, Dungeon, Dark Fantasy',
            'rating' => 8.35,
            'synopsis' => 'Dari Hunter terlemah di dunia, Sung Jinwoo terbangun dengan kekuatan Shadow Monarch.',
            'is_available' => true,
            'total_episodes' => 12,
            'studio' => 'A-1 Pictures',
            'resolution' => '1080p Full HD',
            'has_sub_indo' => true,
        ]);

        $onePiece = Media::create([
            'code' => 'MNG-001',
            'title' => 'One Piece: Egghead Island',
            'type' => 'manga',
            'genre' => 'Aksi, Petualangan, Shonen',
            'rating' => 8.72,
            'synopsis' => 'Luffy dan Bajak Laut Topi Jerami tiba di Pulau Masa Depan Egghead milik Dr. Vegapunk.',
            'is_available' => true,
            'total_chapters' => 1110,
            'author' => 'Eiichiro Oda',
            'is_colored' => true,
            'is_completed' => false,
        ]);

        Media::create([
            'code' => 'MNG-002',
            'title' => 'Chainsaw Man',
            'type' => 'manga',
            'genre' => 'Aksi, Supranatural, Horor',
            'rating' => 8.41,
            'synopsis' => 'Denji hidup di tengah kemiskinan ekstrem bersama iblis gergaji Pochita.',
            'is_available' => true,
            'total_chapters' => 160,
            'author' => 'Tatsuki Fujimoto',
            'is_colored' => false,
            'is_completed' => false,
        ]);

        $user = User::create([
            'name' => 'Farrel Maulana Anshari',
            'email' => 'farrelmaulana.anshari@gmail.com',
            'password' => Hash::make('password123'),
            'tier' => 'VIP Member FlipPlay',
        ]);

        $user->media()->attach([
            $frieren->id => ['progress' => 14],
            $onePiece->id => ['progress' => 1050],
            $blackClover->id => ['progress' => 1],
        ]);
    }
}
