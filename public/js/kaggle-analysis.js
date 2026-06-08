/* kaggle-analysis.js
 * Handles the "Analisis Kaggle" tab.
 */

document.addEventListener('DOMContentLoaded', function () {
    const tabBtnKaggle = document.querySelector('.tab-btn[data-tab="kaggle"]');
    const statsGrid = document.getElementById('kaggle-stats-grid');

    // Load stats when the Kaggle tab becomes visible
    function loadKaggleStats() {
        // First, verify the dataset file exists
        fetch(window.floodWatchConfig.apiDatasetExistsUrl)
            .then(res => res.json())
            .then(data => {
                if (!data.exists) {
                    statsGrid.innerHTML = '<div class="stat-loading">Dataset belum tersedia. Jalankan script download dulu.</div>';
                    return;
                }
                // Dataset exists – fetch statistics
                return fetch(window.floodWatchConfig.apiDatasetStatsUrl)
                    .then(res => res.json())
                    .then(stats => renderStats(stats));
            })
            .catch(err => {
                console.error('Error loading Kaggle stats', err);
                statsGrid.innerHTML = '<div class="stat-loading">Gagal mengambil data.</div>';
            });
    }

    function renderStats(stats) {
        const rows = [];
        const add = (label, value) => rows.push(`<div class="stat-item"><strong>${label}:</strong> ${value}</div>`);
        add('Total Records', stats.total_records);
        add('Hari Banjir', stats.flood_days);
        add('Hari Normal', stats.normal_days);
        add('Rata‑Rata Curah Hujan (Banjir)', `${stats.avg_rainfall_flood} mm`);
        add('Rata‑Rata Curah Hujan (Normal)', `${stats.avg_rainfall_normal} mm`);
        add('Rata‑Rata Suhu (Banjir)', `${stats.avg_temp_flood} °C`);
        add('Rata‑Rata Suhu (Normal)', `${stats.avg_temp_normal} °C`);
        add('Rata‑Rata Kelembaban (Banjir)', `${stats.avg_humidity_flood} %`);
        add('Rata‑Rata Kelembaban (Normal)', `${stats.avg_humidity_normal} %`);
        statsGrid.innerHTML = rows.join('');
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
