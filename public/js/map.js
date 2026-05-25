/* ==========================================================================
   FloodWatch - Interactive Map JavaScript Engine (Leaflet.js)
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {
    
    // 1. Initial State Configs
    const config = window.floodWatchConfig;
    let map = null;
    let markersLayer = L.layerGroup();
    let geojsonLayers = [];
    let activeMarkers = {}; // Keep reference by ID to trigger popups from the sidebar list

    // 2. Initialize Leaflet Map
    function initMap() {
        // Center of Lampung Province, Indonesia (approx.)
        const initialCenter = [-5.2000, 105.1800];
        const initialZoom = 10;

        map = L.map('map', {
            zoomControl: true,
            maxZoom: 18,
            minZoom: 8
        }).setView(initialCenter, initialZoom);

        // CartoDB Dark Matter Tiles (High-end Cyberpunk GIS look)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CartoDB</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        markersLayer.addTo(map);

        // Load Spatial GeoJSON Overlays
        loadGeojsonLayers();

        // Initial Data Fetch
        fetchFloodIncidents();
    }

    // 3. Generate Custom Pulsating DivIcon based on flood status
    function createPulseIcon(status) {
        const statusClass = status.toLowerCase();
        
        return L.divIcon({
            className: 'custom-map-marker',
            iconSize: [24, 24],
            iconAnchor: [12, 12],
            popupAnchor: [0, -12],
            html: `
                <div class="pulse-marker-ring pulse-${statusClass}-ring"></div>
                <div class="pulse-marker-core pulse-${statusClass}-core"></div>
            `
        });
    }

    // 4. Create Detailed Popup HTML
    function createPopupHTML(incident) {
        const dateFormatted = new Date(incident.reported_at).toLocaleString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        return `
            <div class="popup-container">
                <span class="popup-region">${incident.region.name}</span>
                <h3 class="popup-title">${incident.location_name}</h3>
                
                <div class="popup-header" style="margin-top: 0.4rem; padding-bottom: 0.3rem;">
                    <span class="badge-status badge-${incident.status.toLowerCase()}">${incident.status}</span>
                </div>
                
                <div class="popup-detail-grid">
                    <div class="popup-detail-item">
                        <span class="popup-detail-label">Tinggi Air</span>
                        <span class="popup-detail-val" style="color: var(--accent-blue); font-size: 0.95rem;">${incident.water_level} cm</span>
                    </div>
                    <div class="popup-detail-item">
                        <span class="popup-detail-label">Cuaca</span>
                        <span class="popup-detail-val">${incident.weather || 'Tidak Ada Data'}</span>
                    </div>
                    <div class="popup-detail-item" style="grid-column: span 2;">
                        <span class="popup-detail-label">Warga Terdampak</span>
                        <span class="popup-detail-val">${Number(incident.affected_population).toLocaleString('id-ID')} Jiwa</span>
                    </div>
                </div>

                <p class="popup-desc">${incident.description || 'Tidak ada uraian detail kejadian.'}</p>
                
                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.6rem; border-top: 1px solid var(--border-glass); padding-top: 0.4rem; display: flex; align-items: center; justify-content: space-between;">
                    <span>Dilaporkan:</span>
                    <span>${dateFormatted}</span>
                </div>
            </div>
        `;
    }

    // 5. Fetch Flood Data via AJAX
    function fetchFloodIncidents() {
        const searchVal = document.getElementById('search-input').value;
        const regionVal = document.getElementById('region-filter').value;
        const statusVal = document.getElementById('status-filter').value;

        // Build Query URL
        let url = new URL(config.apiFloodsUrl);
        if (searchVal) url.searchParams.append('search', searchVal);
        if (regionVal) url.searchParams.append('region_id', regionVal);
        if (statusVal) url.searchParams.append('status', statusVal);

        // Set Loading State
        document.getElementById('results-counter').innerText = 'Memuat...';

        fetch(url)
            .then(response => response.json())
            .then(data => {
                renderMarkers(data);
                renderSidebarList(data);
            })
            .catch(error => {
                console.error('Error fetching flood data:', error);
                document.getElementById('results-counter').innerText = 'Gagal memuat';
            });
    }

    // 6. Render Custom Markers on the Map
    function renderMarkers(incidents) {
        // Clear existing markers
        markersLayer.clearLayers();
        activeMarkers = {};

        if (incidents.length === 0) return;

        const bounds = [];

        incidents.forEach(inc => {
            const lat = parseFloat(inc.latitude);
            const lng = parseFloat(inc.longitude);

            if (isNaN(lat) || isNaN(lng)) return;

            const marker = L.marker([lat, lng], {
                icon: createPulseIcon(inc.status)
            });

            marker.bindPopup(createPopupHTML(inc));
            markersLayer.addLayer(marker);

            // Save reference to trigger popup programmatically
            activeMarkers[inc.id] = marker;

            bounds.push([lat, lng]);
        });

        // Fit map bounds to show all active markers smoothly if there are any
        if (bounds.length > 0) {
            map.fitBounds(bounds, {
                padding: [50, 50],
                maxZoom: 12
            });
        }
    }

    // 7. Render matching incidents inside Sidebar List
    function renderSidebarList(incidents) {
        const counterEl = document.getElementById('results-counter');
        const listEl = document.getElementById('incidents-list');

        counterEl.innerText = `${incidents.length} Kejadian`;

        listEl.innerHTML = '';

        if (incidents.length === 0) {
            listEl.innerHTML = `
                <div style="padding: 2rem; text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                    Tidak ditemukan data banjir yang cocok dengan filter pencarian.
                </div>
            `;
            return;
        }

        incidents.forEach(inc => {
            const card = document.createElement('div');
            card.className = 'incident-item-card glass-panel';
            card.innerHTML = `
                <div class="incident-card-header">
                    <span class="incident-card-name">${inc.location_name}</span>
                    <span class="badge-status badge-${inc.status.toLowerCase()}" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">${inc.status}</span>
                </div>
                <div class="incident-card-meta">
                    <span class="incident-card-region">${inc.region.name}</span>
                    <span style="color: var(--accent-blue); font-weight: 600;">${inc.water_level} cm</span>
                </div>
                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.5rem; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                    ${inc.description || 'Tidak ada detail.'}
                </div>
            `;

            // Fly and popup when clicking card
            card.addEventListener('click', () => {
                const lat = parseFloat(inc.latitude);
                const lng = parseFloat(inc.longitude);

                if (map && !isNaN(lat) && !isNaN(lng)) {
                    map.flyTo([lat, lng], 14, {
                        duration: 1.2
                    });
                    
                    // Delay slightly to allow transition and open popup
                    setTimeout(() => {
                        if (activeMarkers[inc.id]) {
                            activeMarkers[inc.id].openPopup();
                        }
                    }, 1200);

                    // Collapse sidebar on mobile once selected
                    if (window.innerWidth <= 992) {
                        document.getElementById('map-sidebar').classList.remove('active');
                    }
                }
            });

            listEl.appendChild(card);
        });
    }

    // 8. Load Spatial boundary layers (GeoJSON) from admin upload
    function loadGeojsonLayers() {
        fetch(config.apiGeojsonUrl)
            .then(res => res.json())
            .then(layers => {
                layers.forEach(layer => {
                    fetch(layer.url)
                        .then(geojsonRes => geojsonRes.json())
                        .then(geojsonData => {
                            // Standard Polygon Styling for Lampungs admin boundaries
                            const layerPolygon = L.geoJSON(geojsonData, {
                                style: {
                                    color: 'hsl(174, 100%, 41%)', // Neon Teal
                                    weight: 1.5,
                                    fillColor: 'hsl(174, 100%, 41%)',
                                    fillOpacity: 0.06,
                                    dashArray: '4, 4'
                                }
                            }).addTo(map);

                            // Bind standard popup showing boundary name
                            layerPolygon.bindPopup(`<strong style="font-family: 'Outfit'; font-size: 1rem; color: var(--text-primary);">${layer.name}</strong><br><span style="font-size: 0.85rem; color: var(--text-muted)">Batas Wilayah SIG GeoJSON</span>`);
                            
                            geojsonLayers.push(layerPolygon);
                        })
                        .catch(err => console.error(`Error loading GeoJSON file ${layer.name}:`, err));
                });
            })
            .catch(err => console.error('Error fetching GeoJSON list API:', err));
    }

    // 9. Attach DOM Event Listeners
    // Filter updates
    document.getElementById('search-input').addEventListener('input', debounce(fetchFloodIncidents, 400));
    document.getElementById('region-filter').addEventListener('change', fetchFloodIncidents);
    document.getElementById('status-filter').addEventListener('change', fetchFloodIncidents);

    // Mobile Sidebar controls
    const sidebarEl = document.getElementById('map-sidebar');
    document.getElementById('mobile-toggle-btn').addEventListener('click', () => {
        sidebarEl.classList.add('active');
    });
    document.getElementById('sidebar-close-btn').addEventListener('click', () => {
        sidebarEl.classList.remove('active');
    });

    // Helper Utility: Debounce for fast search input typing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // 10. Execution Boot
    initMap();
});
