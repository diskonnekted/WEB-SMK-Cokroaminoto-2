<?php
// Quran in Text and Audio Plugin (Standalone Implementation)
?>
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar: Surah List -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Daftar Surah</h5>
                </div>
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    <div class="list-group list-group-flush" id="surah-list">
                        <div class="text-center p-3">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Reading Area -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0" id="current-surah-name">Pilih Surah</h4>
                    <div id="audio-controls" style="display: none;">
                        <audio id="quran-audio-player" controls style="height: 40px;">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                </div>
                <div class="card-body" id="quran-content" style="min-height: 400px;">
                    <p class="text-muted text-center mt-5">Silakan pilih surah dari daftar di sebelah kiri untuk mulai membaca dan mendengarkan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const surahListContainer = document.getElementById('surah-list');
    const quranContent = document.getElementById('quran-content');
    const currentSurahName = document.getElementById('current-surah-name');
    const audioControls = document.getElementById('audio-controls');
    const audioPlayer = document.getElementById('quran-audio-player');
    
    // Fetch Surah List
    fetch('https://api.alquran.cloud/v1/surah')
        .then(response => response.json())
        .then(data => {
            if(data.code === 200) {
                surahListContainer.innerHTML = '';
                data.data.forEach(surah => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                    item.innerHTML = `
                        <span>${surah.number}. ${surah.englishName}</span>
                        <small class="text-muted">${surah.name}</small>
                    `;
                    item.onclick = (e) => {
                        e.preventDefault();
                        loadSurah(surah.number, surah.englishName, surah.name);
                        
                        // Highlight active
                        document.querySelectorAll('.list-group-item').forEach(el => el.classList.remove('active'));
                        item.classList.add('active');
                    };
                    surahListContainer.appendChild(item);
                });
            }
        })
        .catch(err => {
            surahListContainer.innerHTML = '<div class="p-3 text-danger">Gagal memuat daftar surah. Periksa koneksi internet Anda.</div>';
        });

    // Load Surah Content
    function loadSurah(number, englishName, arabicName) {
        currentSurahName.innerHTML = `${number}. ${englishName} <span class="text-success">${arabicName}</span>`;
        quranContent.innerHTML = '<div class="text-center mt-5"><div class="spinner-border text-success" role="status"></div><p>Memuat ayat...</p></div>';
        audioControls.style.display = 'none';
        
        // Fetch Arabic Text, Translation (Indonesian), and Audio (Alafasy)
        // Using quran-simple for text, id.indonesian for translation, ar.alafasy for audio
        fetch(`https://api.alquran.cloud/v1/surah/${number}/editions/quran-simple,id.indonesian,ar.alafasy`)
            .then(response => response.json())
            .then(data => {
                if(data.code === 200) {
                    displayVerses(data.data);
                }
            })
            .catch(err => {
                quranContent.innerHTML = '<div class="alert alert-danger">Gagal memuat konten surah.</div>';
            });
    }

    function displayVerses(editions) {
        const arabicData = editions[0]; // quran-simple
        const translationData = editions[1]; // id.indonesian
        const audioData = editions[2]; // ar.alafasy
        
        let html = '';
        
        // Bismillah handling (except Surah 1 and 9)
        if (arabicData.number !== 1 && arabicData.number !== 9) {
            html += `
                <div class="text-center mb-4" style="font-family: 'Amiri', serif; font-size: 2rem;">
                    بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ
                </div>
                <hr>
            `;
        }

        arabicData.ayahs.forEach((ayah, index) => {
            const translation = translationData.ayahs[index].text;
            const audioUrl = audioData.ayahs[index].audio;
            const ayahNumber = ayah.numberInSurah;
            
            // Clean Bismillah from first verse if present (API includes it in text for some editions)
            let arabicText = ayah.text;
            if (arabicData.number !== 1 && index === 0) {
                arabicText = arabicText.replace('بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ', '').trim();
            }

            html += `
                <div class="ayah-container mb-4 pb-3 border-bottom" id="ayah-${ayahNumber}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-success rounded-pill">${ayahNumber}</span>
                        <button class="btn btn-sm btn-outline-secondary play-ayah-btn" onclick="playAyah('${audioUrl}')">
                            <i class="bi bi-play-fill"></i> Play
                        </button>
                    </div>
                    <div class="text-end mb-3" style="font-family: 'Amiri', serif; font-size: 1.8rem; line-height: 2.2;">
                        ${arabicText}
                    </div>
                    <div class="text-start text-muted" style="font-size: 1rem;">
                        ${translation}
                    </div>
                </div>
            `;
        });

        quranContent.innerHTML = html;
        
        // Setup global audio player for the full surah (optional) or just use per-ayah
        // For "Quran in Text and Audio" plugin feel, usually it plays continuously.
        // Let's setup the main audio player to play the first ayah, and playlist logic.
        setupAudioPlaylist(audioData.ayahs);
    }

    let currentPlaylist = [];
    let currentTrackIndex = 0;

    function setupAudioPlaylist(ayahs) {
        currentPlaylist = ayahs;
        currentTrackIndex = 0;
        audioControls.style.display = 'block';
        
        // Initial source
        audioPlayer.src = currentPlaylist[0].audio;
        
        // Auto play next logic
        audioPlayer.onended = function() {
            currentTrackIndex++;
            if (currentTrackIndex < currentPlaylist.length) {
                audioPlayer.src = currentPlaylist[currentTrackIndex].audio;
                audioPlayer.play();
                highlightAyah(currentPlaylist[currentTrackIndex].numberInSurah);
            }
        };
    }
    
    // Expose playAyah globally
    window.playAyah = function(url) {
        audioPlayer.src = url;
        audioPlayer.play();
        // Update index if needed or just play single
    };

    function highlightAyah(number) {
        document.querySelectorAll('.ayah-container').forEach(el => el.classList.remove('bg-light'));
        const el = document.getElementById(`ayah-${number}`);
        if(el) {
            el.classList.add('bg-light');
            el.scrollIntoView({behavior: 'smooth', block: 'center'});
        }
    }
});
</script>

<!-- Add Google Font for Arabic -->
<link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
<!-- Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
