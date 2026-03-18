<div class="sidebar-widget">
    <div class="section-title">
        <h2>Radio Sekolah</h2>
    </div>
    <div class="cakra-fm-player" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px; border-radius: 10px; border: 1px solid #ddd; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        
        <!-- Logo -->
        <div style="margin-bottom: 15px; position: relative; display: inline-block;">
            <img src="cakrafm.png" alt="Cakra FM Logo" style="width: 120px; height: 120px; object-fit: cover; border-radius: 10px; border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
            <div id="on-air-badge" style="position: absolute; bottom: 5px; right: 5px; background: #dc3545; color: white; font-size: 0.6rem; padding: 2px 6px; border-radius: 4px; font-weight: bold; display: none;">ON AIR</div>
        </div>

        <h4 style="margin: 0; color: #333; font-weight: 700;">CAKRA FM</h4>
        <p style="font-size: 0.8rem; color: #666; margin-bottom: 15px;">Radio Komunitas Pelajar</p>

        <!-- Visualizer Placeholder -->
        <div id="visualizer" style="height: 30px; display: flex; align-items: center; justify-content: center; gap: 3px; margin-bottom: 15px; opacity: 0.3;">
            <div class="bar" style="width: 4px; height: 10px; background: #0d6efd; transition: height 0.1s;"></div>
            <div class="bar" style="width: 4px; height: 15px; background: #0d6efd; transition: height 0.1s;"></div>
            <div class="bar" style="width: 4px; height: 20px; background: #0d6efd; transition: height 0.1s;"></div>
            <div class="bar" style="width: 4px; height: 12px; background: #0d6efd; transition: height 0.1s;"></div>
            <div class="bar" style="width: 4px; height: 18px; background: #0d6efd; transition: height 0.1s;"></div>
        </div>

        <!-- Audio Element -->
        <audio id="cakra-audio" preload="none">
            <source src="<?php echo !empty($settings['cakrafm_stream_url']) ? htmlspecialchars($settings['cakrafm_stream_url']) : ''; ?>" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>

        <!-- Controls -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
            <button id="btn-play" onclick="toggleRadio()" style="width: 50px; height: 50px; border-radius: 50%; border: none; background: #0d6efd; color: white; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(13, 110, 253, 0.4); transition: transform 0.1s;">
                <i class="fas fa-play" style="margin-left: 3px;"></i>
            </button>
        </div>

        <!-- Status Text -->
        <div id="radio-status" style="margin-top: 15px; font-size: 0.8rem; font-weight: bold; color: #555;">
            OFF AIR
        </div>

        <script>
            let isPlaying = false;
            let visualizerInterval;
            const audio = document.getElementById('cakra-audio');
            
            function toggleRadio() {
                const btn = document.getElementById('btn-play');
                const status = document.getElementById('radio-status');
                const badge = document.getElementById('on-air-badge');
                const viz = document.getElementById('visualizer');
                const icon = btn.querySelector('i');
                const streamUrl = "<?php echo !empty($settings['cakrafm_stream_url']) ? htmlspecialchars($settings['cakrafm_stream_url']) : ''; ?>";

                if (!isPlaying) {
                    // Check if URL is set
                    if (!streamUrl) {
                        alert("URL Streaming belum diatur. Silakan hubungi admin.");
                        return;
                    }

                    // Try to play
                    audio.play().then(() => {
                        // Play state (success)
                        isPlaying = true;
                        icon.classList.remove('fa-play');
                        icon.classList.add('fa-stop');
                        icon.style.marginLeft = '0';
                        btn.style.background = '#dc3545';
                        btn.style.boxShadow = '0 2px 5px rgba(220, 53, 69, 0.4)';
                        
                        status.innerText = "NOW PLAYING: LIVE STREAM";
                        status.style.color = "#0d6efd";
                        
                        badge.style.display = "block";
                        viz.style.opacity = "1";
                        
                        startVisualizer();
                    }).catch(error => {
                        console.error("Playback failed:", error);
                        alert("Gagal memutar radio. Pastikan server streaming aktif.");
                    });

                } else {
                    // Stop state
                    audio.pause();
                    audio.currentTime = 0; // Reset stream buffer if possible

                    isPlaying = false;
                    icon.classList.remove('fa-stop');
                    icon.classList.add('fa-play');
                    icon.style.marginLeft = '3px';
                    btn.style.background = '#0d6efd';
                    btn.style.boxShadow = '0 2px 5px rgba(13, 110, 253, 0.4)';
                    
                    status.innerText = "OFF AIR";
                    status.style.color = "#555";
                    
                    badge.style.display = "none";
                    viz.style.opacity = "0.3";
                    
                    stopVisualizer();
                }
            }

            function startVisualizer() {
                const bars = document.querySelectorAll('#visualizer .bar');
                visualizerInterval = setInterval(() => {
                    bars.forEach(bar => {
                        const height = Math.floor(Math.random() * 20) + 5;
                        bar.style.height = height + 'px';
                    });
                }, 100);
            }

            function stopVisualizer() {
                clearInterval(visualizerInterval);
                const bars = document.querySelectorAll('#visualizer .bar');
                bars.forEach(bar => {
                    bar.style.height = '10px';
                });
            }
        </script>
    </div>
</div>
