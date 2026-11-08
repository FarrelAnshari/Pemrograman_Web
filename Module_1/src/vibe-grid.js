const API_URL = "https://api.tenrai.org/v1/top/anime?limit=24";
const animeGrid = document.querySelector("#anime-grid");
const gridStatus = document.querySelector("#grid-status");
const searchInput = document.querySelector("#anime-search");
const retryButton = document.querySelector("#retry-load");
const auraButtons = document.querySelectorAll(".aura-button");
let animeItems = [];
let selectedAura = "all";
let hasLoaded = false;
let activeRequest;

const escapeHTML = (value = "") => String(value).replace(/[&<>"']/g, (character) => ({
  "&": "&amp;",
  "<": "&lt;",
  ">": "&gt;",
  '"': "&quot;",
  "'": "&#39;"
})[character]);

function matchesAura(anime) {
  const genreIds = anime.genres.map((genre) => genre.mal_id);
  if (selectedAura === "energy") return genreIds.some((id) => [1, 2, 4, 17, 18, 30].includes(id));
  if (selectedAura === "melancholy") return genreIds.some((id) => [8, 22, 36, 40].includes(id));
  if (selectedAura === "binge") return anime.episodes !== null && anime.episodes <= 24;
  return true;
}

function renderAnime() {
  if (!hasLoaded) return;

  const query = searchInput.value.trim().toLocaleLowerCase();
  const visibleAnime = animeItems.filter((anime) => {
    const title = (anime.title_english || anime.title || "").toLocaleLowerCase();
    return title.includes(query) && matchesAura(anime);
  });

  animeGrid.innerHTML = visibleAnime.map((anime) => {
    const title = escapeHTML(anime.title_english || anime.title || "Untitled");
    const image = escapeHTML(anime.images?.jpg?.large_image_url || anime.images?.jpg?.image_url || "");
    const rawSynopsis = anime.synopsis || "Sinopsis belum tersedia.";
    const synopsis = escapeHTML(rawSynopsis.slice(0, 170));
    const year = anime.year || anime.aired?.prop?.from?.year || "Tahun tidak diketahui";
    const genres = anime.genres.slice(0, 2).map((genre) => escapeHTML(genre.name)).join(" · ");
    const episodes = anime.episodes ? `${anime.episodes} episode` : "Jumlah episode TBA";

    return `
      <article class="overflow-hidden rounded-2xl border border-white/10 bg-white text-ink shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
        <div class="relative aspect-[3/4] overflow-hidden bg-flip-soft">
          <img src="${image}" alt="Poster ${title}" class="h-full w-full object-cover" loading="lazy" />
          <span class="absolute left-3 top-3 rounded-full bg-ink/90 px-3 py-1 text-xs font-bold text-play">★ ${escapeHTML(anime.score ?? "N/A")}</span>
        </div>
        <div class="p-4">
          <p class="text-xs font-semibold uppercase tracking-wide text-flip">${escapeHTML(anime.type || "Anime")} · ${escapeHTML(year)}</p>
          <h2 class="mt-1 line-clamp-2 min-h-12 text-lg font-extrabold">${title}</h2>
          <p class="mt-2 text-xs text-ink/60">${genres || "Genre belum tersedia"} · ${escapeHTML(episodes)}</p>
          <p class="mt-3 line-clamp-3 min-h-[4.5rem] text-sm leading-relaxed text-ink/75">${synopsis}${rawSynopsis.length > 170 ? "…" : ""}</p>
        </div>
      </article>
    `;
  }).join("");

  gridStatus.textContent = visibleAnime.length
    ? `${visibleAnime.length} judul ditemukan`
    : "Tidak ada judul yang cocok. Coba kata kunci atau Aura lain.";
  animeGrid.setAttribute("aria-busy", "false");
}

searchInput.addEventListener("input", renderAnime);

auraButtons.forEach((button) => {
  button.addEventListener("click", () => {
    selectedAura = button.dataset.aura;
    auraButtons.forEach((auraButton) => {
      const isSelected = auraButton === button;
      auraButton.setAttribute("aria-pressed", String(isSelected));
      auraButton.classList.toggle("border-play", isSelected);
      auraButton.classList.toggle("bg-play", isSelected);
      auraButton.classList.toggle("text-ink", isSelected);
      auraButton.classList.toggle("text-play", !isSelected);
    });
    renderAnime();
  });
});

function showOfflineState() {
  activeRequest?.abort();
  activeRequest = undefined;
  animeItems = [];
  hasLoaded = false;
  animeGrid.innerHTML = "";
  animeGrid.setAttribute("aria-busy", "false");
  gridStatus.textContent = "Koneksi internet terputus. Hubungkan kembali untuk memuat rekomendasi anime.";
  retryButton.classList.add("hidden");
}

retryButton.addEventListener("click", loadAnime);
window.addEventListener("offline", showOfflineState);
window.addEventListener("online", loadAnime);

async function loadAnime() {
  if (!navigator.onLine) {
    showOfflineState();
    return;
  }

  activeRequest?.abort();
  const requestController = new AbortController();
  activeRequest = requestController;
  animeGrid.setAttribute("aria-busy", "true");
  gridStatus.textContent = "Memuat rekomendasi anime...";
  retryButton.classList.add("hidden");

  try {
    const response = await fetch(API_URL, { signal: requestController.signal });
    if (!response.ok) throw new Error(`Tenrai API merespons dengan status ${response.status}.`);
    const result = await response.json();
    if (requestController.signal.aborted) return;
    if (!Array.isArray(result.data)) throw new Error("Format data dari Tenrai API tidak sesuai.");

    animeItems = result.data;
    hasLoaded = true;
    renderAnime();
  } catch (error) {
    if (requestController.signal.aborted) return;
    console.error("Gagal memuat rekomendasi anime:", error);
    gridStatus.textContent = "Rekomendasi gagal dimuat. Periksa koneksi internet lalu coba lagi.";
    animeGrid.setAttribute("aria-busy", "false");
    retryButton.classList.remove("hidden");
  } finally {
    if (activeRequest === requestController) activeRequest = undefined;
  }
}

loadAnime();
