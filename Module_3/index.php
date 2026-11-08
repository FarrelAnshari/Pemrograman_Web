<?php

/**
 * ============================================================================
 * FlipPlay Backend Engine - Simulasi Demo Modul 3
 * Paradigma Object-Oriented Programming (OOP) PHP 8.x
 * ============================================================================
 * 
 * Memenuhi 8 Ketentuan Praktikum:
 * 1. Minimal 3 class (Tersedia 5 class: Media, Anime, Manga, User, FlipPlayEngine)
 * 2. Menggunakan Constructor (__construct)
 * 3. Memiliki Property dan Method
 * 4. Menerapkan Access Modifier (public, protected, private)
 * 5. Menerapkan Inheritance (Anime extends Media, Manga extends Media)
 * 6. Menerapkan Type Hinting dan Return Type
 * 7. Menggunakan Namespace (App\Models, App\Services)
 * 8. Memiliki file index.php sebagai entry point simulasi terminal
 */

// Autoloader sederhana untuk memuat class secara otomatis berdasarkan namespace
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Import namespace class yang digunakan
use App\Models\Media;
use App\Models\Anime;
use App\Models\Manga;
use App\Models\User;
use App\Services\FlipPlayEngine;

// Helper function untuk mencetak garis pemisah
function printDivider(string $title = ""): void
{
    echo PHP_EOL;
    if ($title !== "") {
        echo "=================== [ {$title} ] ===================" . PHP_EOL;
    } else {
        echo str_repeat("=", 68) . PHP_EOL;
    }
}

// ============================================================================
// HEADER BANNER SIMULASI
// ============================================================================
printDivider();
echo "       🎬 📖 FLIPPLAY BACKEND ENGINE - PROTOTYPE SIMULATOR 📖 🎬       " . PHP_EOL;
echo "               Laboratorium Informatika - Modul 3 PHP OOP              " . PHP_EOL;
printDivider();

// Inisialisasi Service Engine
$engine = new FlipPlayEngine("FlipPlay Core Engine", "v3.0-OOP");
echo "Status Engine : " . $engine->getPlatformInfo() . " BERHASIL DIJALANKAN" . PHP_EOL;
echo "Waktu Sistem  : " . date("Y-m-d H:i:s") . PHP_EOL;


// ============================================================================
// 1. MENAMBAHKAN MEDIA KE DALAM LIBRARY
// ============================================================================
printDivider("1. PROSES PENAMBAHAN MEDIA (ANIME & MANGA)");

// Objek Anime (Inheritance dari Media)
$anime1 = new Anime(
    id: "ANM-001",
    title: "Black Clover: Sword of the Wizard King",
    genre: "Aksi, Fantasi, Shonen",
    rating: 8.15,
    synopsis: "Asta yang terlahir tanpa sihir tetap gigih membuktikan mimpinya menjadi Kaisar Sihir.",
    totalEpisodes: 170,
    studio: "Studio Pierrot",
    resolution: "1080p Full HD",
    hasSubIndo: true
);

$anime2 = new Anime(
    id: "ANM-002",
    title: "Sousou no Frieren (Beyond Journey's End)",
    genre: "Petualangan, Fantasi, Drama",
    rating: 9.24,
    synopsis: "Penyihir elf memulai perjalanan baru demi memahami arti waktu manusia pasca kematian pahlawan.",
    totalEpisodes: 28,
    studio: "Madhouse",
    resolution: "4K Ultra HD",
    hasSubIndo: true
);

$anime3 = new Anime(
    id: "ANM-003",
    title: "Solo Leveling: Arise",
    genre: "Aksi, Dungeon, Dark Fantasy",
    rating: 8.35,
    synopsis: "Dari Hunter terlemah di dunia, Sung Jinwoo terbangun dengan kekuatan Shadow Monarch.",
    totalEpisodes: 12,
    studio: "A-1 Pictures",
    resolution: "1080p Full HD",
    hasSubIndo: true
);

// Objek Manga (Inheritance dari Media)
$manga1 = new Manga(
    id: "MNG-001",
    title: "One Piece: Egghead Island",
    genre: "Petualangan, Aksi, Fantasi",
    rating: 8.72,
    synopsis: "Luffy dan Bajak Laut Topi Jerami mengungkap rahasia Abad Kekosongan di pulau masa depan.",
    totalChapters: 1110,
    author: "Eiichiro Oda",
    isColor: false,
    isCompleted: false
);

$manga2 = new Manga(
    id: "MNG-002",
    title: "Chainsaw Man",
    genre: "Aksi, Horor, Dark Comedy",
    rating: 8.41,
    synopsis: "Denji menjalani kehidupan baru sebagai pemburu iblis setelah menyatu dengan Pochita.",
    totalChapters: 160,
    author: "Tatsuki Fujimoto",
    isColor: false,
    isCompleted: false
);

// Memasukkan ke dalam Engine
$engine->addMedia($anime1);
$engine->addMedia($anime2);
$engine->addMedia($anime3);
$engine->addMedia($manga1);
$engine->addMedia($manga2);

echo "[SUCCESS] Berhasil mendaftarkan 3 judul Anime & 2 judul Manga." . PHP_EOL;
echo "Total Koleksi di Library: " . $engine->getTotalMediaCount() . " item media." . PHP_EOL;


// ============================================================================
// 2. MENAMPILKAN DAFTAR MEDIA YANG TERSEDIA
// ============================================================================
printDivider("2. KATALOG MEDIA TERSEDIA DI FLIPPLAY");

printf("%-8s | %-36s | %-12s | %-7s | %-12s\n", "ID", "JUDUL", "TIPE", "RATING", "UNIT/RILIS");
echo str_repeat("-", 85) . PHP_EOL;

foreach ($engine->getAllMedia() as $item) {
    $unit = ($item instanceof Anime)
        ? $item->getTotalEpisodes() . " Eps"
        : ($item instanceof Manga ? $item->getTotalChapters() . " Bab" : "-");

    printf(
        "%-8s | %-36s | %-12s | ★ %-5.2f | %-12s\n",
        $item->getId(),
        substr($item->getTitle(), 0, 36),
        $item instanceof Anime ? "Anime" : "Manga",
        $item->getRating(),
        $unit
    );
}


// ============================================================================
// 3. MENCARI MEDIA BERDASARKAN JUDUL
// ============================================================================
printDivider("3. FITUR PENCARIAN MEDIA BERDASARKAN JUDUL");

$keyword = "Frieren";
echo "Kata Kunci Pencarian : '{$keyword}'" . PHP_EOL;
$searchResults = $engine->searchByTitle($keyword);

if (!empty($searchResults)) {
    echo "Ditemukan " . count($searchResults) . " media yang cocok:" . PHP_EOL;
    foreach ($searchResults as $result) {
        echo " -> [{$result->getId()}] {$result->getTitle()} ({$result->getMediaType()}) - Rating: ★ {$result->getRating()}" . PHP_EOL;
        // Demonstrasi method polimorfik consume()
        echo "    Aksi Pengguna: " . $result->consume() . PHP_EOL;
    }
} else {
    echo "Media dengan kata kunci '{$keyword}' tidak ditemukan." . PHP_EOL;
}


// ============================================================================
// 4. MENAMPILKAN INFORMASI DETAIL SUATU MEDIA
// ============================================================================
printDivider("4. INFORMASI DETAIL MEDIA (MEDIA DETAILS)");

$targetId = "ANM-003"; // Solo Leveling
$detailMedia = $engine->findById($targetId);

if ($detailMedia !== null) {
    echo "Memuat detail spesifik untuk ID: [{$targetId}]..." . PHP_EOL;
    foreach ($detailMedia->getDetails() as $key => $value) {
        printf("  %-20s : %s\n", $key, $value);
    }
    // Simulasi putar episode tertentu
    if ($detailMedia instanceof Anime) {
        echo "  Uji Pemutaran        : " . $detailMedia->playEpisode(3) . PHP_EOL;
    }
}


// ============================================================================
// 5. MENAMPILKAN KOLEKSI MEDIA MILIK PENGGUNA
// ============================================================================
printDivider("5. KOLEKSI PUSTAKA PENGGUNA (USER WATCHLIST & READING LIST)");

// Membuat objek User
$user = new User(
    id: "USR-007",
    name: "Farrel Maulana Anshari",
    email: "farrelmaulana.anshari@gmail.com",
    tier: "VIP Member FlipPlay"
);

echo "Profil Pengguna : {$user->getName()} ({$user->getEmail()}) - Status: [{$user->getTier()}]" . PHP_EOL;

// User menambahkan anime dan manga ke watchlist
$user->addToWatchlist($anime2); // Frieren
$user->addToWatchlist($manga1); // One Piece
$user->addToWatchlist($anime1); // Black Clover

// Update progres user
$user->updateProgress($anime2->getId(), 14); // Sudah sampai episode 14
$user->updateProgress($manga1->getId(), 1050); // Sudah baca sampai bab 1050

echo "Koleksi yang sedang dinikmati Farrel saat ini:" . PHP_EOL;
echo str_repeat("-", 80) . PHP_EOL;

foreach ($user->getWatchlist() as $index => $item) {
    $current = $user->getProgress($item->getId());
    $tipe = ($item instanceof Anime) ? "Tontonan (Eps {$current})" : "Bacaan (Bab {$current})";

    printf(
        " %d. [%-5s] %-38s | Progres: %-18s\n",
        $index + 1,
        $item instanceof Anime ? "ANIME" : "MANGA",
        substr($item->getTitle(), 0, 38),
        $tipe
    );
}

printDivider("SIMULASI DEMO MODUL 3 FLIPPLAY SELESAI DENGAN SUKSES");
echo "Seluruh 8 ketentuan praktikum & 5 alur simulasi telah berhasil dijalankan." . PHP_EOL . PHP_EOL;
