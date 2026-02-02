<div class="sidebar-widget">
    <div class="section-title">
        <h2>Quran Radio</h2>
    </div>
    <div class="quran-radio-player" style="background: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
        <div style="margin-bottom: 10px; text-align: center;">
            <!-- Icon Quran SVG (Inline) -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50" height="50" style="margin-bottom: 10px; fill: #008f4c;">
                <path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zm0 13.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z"/>
                <path d="M17.5 10.5c.88 0 1.73.09 2.5.26V9.24c-.79-.15-1.64-.24-2.5-.24-1.7 0-3.24.29-4.5.83v1.66c1.13-.64 2.7-.99 4.5-.99zM13 12.49v1.66c1.13-.64 2.7-.99 4.5-.99.88 0 1.73.09 2.5.26v-1.52c-.79-.15-1.64-.24-2.5-.24-1.7 0-3.24.29-4.5.83zM17.5 14.33c-1.7 0-3.24.29-4.5.83v1.66c1.13-.64 2.7-.99 4.5-.99.88 0 1.73.09 2.5.26v-1.52c-.79-.15-1.64-.24-2.5-.24z"/>
            </svg>
            <p style="font-size: 0.9rem; margin: 0;">Dengarkan Murottal & Terjemahan</p>
            <p id="player-status" style="font-size: 0.7rem; color: #888; margin-top: 5px;">Ready to play</p>
        </div>
        
        <!-- Player controls -->
        <audio id="quran-player" controls style="width: 100%; margin-bottom: 10px;" preload="none">
            <source src="https://qurango.net/radio/tarjama_indonesian" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
        
        <label for="station-select" style="font-size: 0.8rem; font-weight: bold;">Pilih Saluran:</label>
        <select id="station-select" onchange="changeStation(this.value)" style="width: 100%; margin-top: 5px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="https://qurango.net/radio/tarjama_indonesian">Terjemahan Indonesia</option>
            <option value="https://qurango.net/radio/mishary_alafasi">Mishary Al-Afasy</option>
            <option value="https://qurango.net/radio/abdulbasit_abdulsamad_warsh">Abdul Basit (Warsh)</option>
            <option value="https://qurango.net/radio/sudais_shuraym">Sudais & Shuraym</option>
            <option value="https://qurango.net/radio/radio_ar">Main Arabic Radio</option>
        </select>
        
        <script>
            var player = document.getElementById('quran-player');
            var statusDisplay = document.getElementById('player-status');

            player.addEventListener('playing', function() {
                statusDisplay.innerText = "Playing...";
                statusDisplay.style.color = "green";
            });

            player.addEventListener('waiting', function() {
                statusDisplay.innerText = "Buffering...";
                statusDisplay.style.color = "orange";
            });

            player.addEventListener('error', function(e) {
                console.error("Error playing audio:", e);
                statusDisplay.innerText = "Error loading stream. Try another station.";
                statusDisplay.style.color = "red";
            });

            function changeStation(url) {
                statusDisplay.innerText = "Connecting...";
                statusDisplay.style.color = "blue";
                player.src = url;
                player.load(); // Important to reload the source
                var playPromise = player.play();

                if (playPromise !== undefined) {
                    playPromise.then(_ => {
                        // Automatic playback started!
                    })
                    .catch(error => {
                        // Auto-play was prevented
                        statusDisplay.innerText = "Click Play to start";
                        console.log("Autoplay prevented:", error);
                    });
                }
            }
        </script>
        
        <div style="margin-top: 10px; text-align: center; font-size: 0.7rem; color: #999;">
            Powered by MP3Quran.net
        </div>
    </div>
</div>