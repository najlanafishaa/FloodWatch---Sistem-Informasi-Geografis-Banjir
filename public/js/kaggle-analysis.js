/* kaggle-analysis.js
 * Handles the "Analisis Kaggle" tab.
 */

document.addEventListener('DOMContentLoaded', function () {
    const tabBtnKaggle = document.querySelector('.tab-btn[data-tab="kaggle"]');
    const statsGrid = document.getElementById('kaggle-stats-grid');
    const recordsList = document.getElementById('historical-records-list');

    // Load stats when the Kaggle tab becomes visible
    function loadKaggleStats() {
        // First, verify the dataset file exists
        fetch(window.floodWatchConfig.apiDatasetExistsUrl)
            .then(res => res.json())
            .then(data => {
                if (!data.exists) {
                    statsGrid.innerHTML = '<div class="stat-loading">Dataset belum tersedia di database.</div>';
                    if (recordsList) recordsList.innerHTML = '<div class="stat-loading">Tidak ada data historis.</div>';
                    return;
                }
                
                // Dataset exists – fetch statistics
                statsGrid.innerHTML = '<div class="stat-loading">Memuat analisis...</div>';
                return fetch(window.floodWatchConfig.apiDatasetStatsUrl)
                    .then(res => res.json())
                    .then(stats => renderStats(stats));
            })
            .catch(err => {
                console.error('Error loading Kaggle stats', err);
                statsGrid.innerHTML = '<div class="stat-loading" style="color: #EF476F;">Gagal mengambil data.</div>';
            });
    }

    function renderStats(stats) {
        if (!stats || stats.total_records === 0) {
            statsGrid.innerHTML = '<div class="stat-loading">Dataset kosong.</div>';
            return;
        }

        const addStatCard = (label, value, isWide = false) => {
            return `
                <div class="${isWide ? 'stat-card-wide' : 'stat-card'}">
                    <span class="stat-card-title">${label}</span>
                    <span class="stat-card-val">${value}</span>
                </div>
            `;
        };

        const rows = [];
        rows.push(addStatCard('Total Records', stats.total_records));
        rows.push(addStatCard('Hari Banjir', stats.flood_days));
        rows.push(addStatCard('Curah Hujan (Banjir)', `${stats.avg_rainfall_flood} mm`));
        rows.push(addStatCard('Curah Hujan (Normal)', `${stats.avg_rainfall_normal} mm`));
        rows.push(addStatCard('Suhu Rata-rata', `${stats.avg_temp_flood} °C`));
        rows.push(addStatCard('Kelembaban', `${stats.avg_humidity_flood} %`));
        
        statsGrid.innerHTML = rows.join('');

        // Render Recent Records
        if (recordsList && stats.recent_records) {
            recordsList.innerHTML = '';
            stats.recent_records.forEach(record => {
                const isFlood = record.flood_occurred == 1;
                const statusBadge = isFlood 
                    ? '<span class="badge-status badge-awas" style="padding: 0.15rem 0.4rem; font-size: 0.6rem;">Banjir</span>' 
                    : '<span class="badge-status badge-aman" style="padding: 0.15rem 0.4rem; font-size: 0.6rem;">Normal</span>';
                
                const card = `
                    <div class="historical-item-card">
                        <div class="hist-card-header">
                            <span class="hist-card-date">${record.date}</span>
                            ${statusBadge}
                        </div>
                        <div class="hist-card-grid">
                            <div class="hist-card-grid-item">Hujan: <span>${record.rainfall} mm</span></div>
                            <div class="hist-card-grid-item">Suhu: <span>${record.avg_temperature} °C</span></div>
                            <div class="hist-card-grid-item">Lembab: <span>${record.avg_humidity}%</span></div>
                        </div>
                    </div>
                `;
                recordsList.innerHTML += card;
            });
        }
    }

    // Tab switching logic – show/hide content panels
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.sidebar-scrollable-content');

    function switchTab(targetTab) {
        // Hide all content panels
        tabContents.forEach(content => content.classList.add('d-none'));
        // Show target panel
        const targetContent = document.getElementById(`tab-${targetTab}-content`);
        if (targetContent) {
            targetContent.classList.remove('d-none');
        }
        // Update active button styles
        tabButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.tab === targetTab));
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            switchTab(tab);
            if (tab === 'kaggle') {
                // Load stats when Kaggle tab is activated
                setTimeout(loadKaggleStats, 100);
            }
        });
    });
    // Initial activation for realtime tab (already visible)
    switchTab('realtime');
});
