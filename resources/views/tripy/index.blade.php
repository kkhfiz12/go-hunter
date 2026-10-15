<x-layouts.app
    title="TRIPY — Peta Eksplorasi Kota & Rekomendasi Sekitar"
    description="Peta interaktif eksplorasi landmark kota, cagar budaya, kuliner lokal legendaris, dan pusat oleh-oleh dengan fitur check-in berhadiah XP."
    :hideFooter="true"
>
    @push('styles')
        <!-- Leaflet.js CSS in Head -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
        <style>
            /* Custom Leaflet Marker Styling */
            .custom-div-icon {
                background: transparent;
                border: none;
            }
            .pin-pulse {
                box-shadow: 0 0 0 0 rgba(2, 132, 199, 0.7);
                animation: pulse-ring 1.8s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            @keyframes pulse-ring {
                0% { box-shadow: 0 0 0 0 rgba(2, 132, 199, 0.7); }
                70% { box-shadow: 0 0 0 16px rgba(2, 132, 199, 0); }
                100% { box-shadow: 0 0 0 0 rgba(2, 132, 199, 0); }
            }
            .leaflet-container {
                font-family: 'Plus Jakarta Sans', sans-serif !important;
                width: 100% !important;
                height: 100% !important;
                background: #0f172a !important;
            }
            .leaflet-popup-content-wrapper {
                border-radius: 1rem;
                padding: 0;
                overflow: hidden;
                box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
            }
            .leaflet-popup-content {
                margin: 0 !important;
                line-height: 1.4;
            }
        </style>
        <!-- Leaflet JS loaded early so L is always available -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    @endpush

    <!-- Define tripyExplorer globally BEFORE Alpine evaluates x-data -->
    <script>
        function tripyExplorer(config) {
            return {
                map: null,
                cities: config.cities || [],
                activeCity: config.activeCity || null,
                destinations: config.destinations || [],
                checkedInSpotIds: config.checkedInSpotIds || [],
                isAuthenticated: config.isAuthenticated,
                userPoints: config.userPoints || 0,
                csrfToken: config.csrfToken,

                activeCategoryFilter: 'all',
                selectedDestination: null,
                selectedSpot: null,
                claiming: false,
                showCheckinModal: false,

                destinationMarkersLayer: null,
                spotsMarkersLayer: null,
                radiusCircleLayer: null,

                toast: {
                    show: false,
                    type: 'success',
                    title: '',
                    message: ''
                },

                closeCheckinModal() {
                    this.showCheckinModal = false;
                    if (window.location.hash === '#checkin') {
                        history.replaceState(null, '', window.location.pathname + window.location.search);
                    }
                },

                get filteredSpots() {
                    if (!this.selectedDestination || !this.selectedDestination.spots) {
                        return [];
                    }
                    if (this.activeCategoryFilter === 'all') {
                        return this.selectedDestination.spots;
                    }
                    return this.selectedDestination.spots.filter(s => s.category === this.activeCategoryFilter);
                },

                initMap() {
                    const startInit = () => {
                        if (typeof L === 'undefined') {
                            setTimeout(startInit, 100);
                            return;
                        }

                        const mapEl = document.getElementById('tripy-map');
                        if (!mapEl) {
                            setTimeout(startInit, 100);
                            return;
                        }

                        const defaultLat = this.activeCity ? parseFloat(this.activeCity.latitude) : -6.2088;
                        const defaultLng = this.activeCity ? parseFloat(this.activeCity.longitude) : 106.8456;

                        if (this.map) {
                            this.map.remove();
                        }

                        // Initialize Leaflet map
                        this.map = L.map('tripy-map', {
                            center: [defaultLat, defaultLng],
                            zoom: 13,
                            zoomControl: false
                        });

                        // Tile layer
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors',
                            maxZoom: 19
                        }).addTo(this.map);

                        // Zoom control at top right
                        L.control.zoom({ position: 'topright' }).addTo(this.map);

                        // Layers
                        this.destinationMarkersLayer = L.layerGroup().addTo(this.map);
                        this.spotsMarkersLayer = L.layerGroup().addTo(this.map);

                        this.renderDestinationPins();

                        // Force Leaflet to recalculate container dimensions
                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize();
                            }
                        }, 250);

                        // Watch filter changes to update spots
                        this.$watch('activeCategoryFilter', () => {
                            if (this.selectedDestination) {
                                this.renderSpotPins(this.selectedDestination);
                            }
                        });

                        // Check hash on load & listen for hash changes
                        if (window.location.hash === '#checkin') {
                            this.showCheckinModal = true;
                        }
                        window.addEventListener('hashchange', () => {
                            if (window.location.hash === '#checkin') {
                                this.showCheckinModal = true;
                            }
                        });
                    };

                    startInit();
                },

                switchCity(city) {
                    this.activeCity = city;
                    this.activeCategoryFilter = 'all';
                    this.selectedDestination = null;
                    this.selectedSpot = null;

                    // Update URL without page reload
                    window.history.pushState(null, '', '/tripy?city=' + city.slug);
                    if (this.spotsMarkersLayer) {
                        this.spotsMarkersLayer.clearLayers();
                    }
                    if (this.radiusCircleLayer && this.map) {
                        this.map.removeLayer(this.radiusCircleLayer);
                        this.radiusCircleLayer = null;
                    }

                    if (this.map) {
                        this.map.flyTo([parseFloat(city.latitude), parseFloat(city.longitude)], 13, {
                            duration: 1.5
                        });
                    }

                    // Fetch city data
                    fetch(`/tripy/cities/${city.slug}`)
                        .then(res => res.json())
                        .then(data => {
                            this.destinations = data.destinations;
                            this.renderDestinationPins();
                        })
                        .catch(err => {
                            console.error('Failed to load city data:', err);
                        });
                },

                setCategoryFilter(category) {
                    this.activeCategoryFilter = category;
                    if (this.selectedDestination) {
                        this.renderSpotPins(this.selectedDestination);
                        const spots = this.filteredSpots;
                        if (spots && spots.length > 0) {
                            const latLngs = spots
                                .filter(s => s.latitude && s.longitude)
                                .map(s => [parseFloat(s.latitude), parseFloat(s.longitude)]);
                            if (latLngs.length === 1 && this.map) {
                                this.map.flyTo(latLngs[0], 16, { duration: 1.0 });
                            } else if (latLngs.length > 1 && this.map) {
                                this.map.flyToBounds(L.latLngBounds(latLngs), { padding: [60, 60], maxZoom: 16, duration: 1.2 });
                            }
                        }
                    } else {
                        this.renderAllCitySpotsByCategory(category);
                    }
                },

                renderAllCitySpotsByCategory(category) {
                    if (!this.spotsMarkersLayer || !this.map) return;
                    this.spotsMarkersLayer.clearLayers();

                    if (category === 'all') {
                        if (this.destinations.length > 0) {
                            const destCoords = this.destinations
                                .filter(d => d.latitude && d.longitude)
                                .map(d => [parseFloat(d.latitude), parseFloat(d.longitude)]);
                            if (destCoords.length > 0) {
                                this.map.flyToBounds(L.latLngBounds(destCoords), { padding: [60, 60], maxZoom: 14, duration: 1.2 });
                            }
                        }
                        return;
                    }

                    let matchingSpots = [];
                    this.destinations.forEach(dest => {
                        if (dest.spots) {
                            dest.spots.forEach(spot => {
                                if (spot.category === category) {
                                    matchingSpots.push(spot);
                                }
                            });
                        }
                    });

                    if (matchingSpots.length === 0) {
                        const catLabel = category === 'kuliner' ? 'Kuliner Khas' : (category === 'oleh_oleh' ? 'Pusat Oleh-Oleh' : 'Spot Foto');
                        this.showToast('info', 'Spot Belum Tersedia', `Belum ada spot ${catLabel} di kota ini.`);
                        return;
                    }

                    matchingSpots.forEach(spot => {
                        this.addSpotMarker(spot);
                    });

                    // Direct map: fly to fit all pins of the selected category so user immediately sees them
                    const latLngs = matchingSpots
                        .filter(s => s.latitude && s.longitude)
                        .map(s => [parseFloat(s.latitude), parseFloat(s.longitude)]);

                    if (latLngs.length === 1) {
                        this.map.flyTo(latLngs[0], 16, { duration: 1.2 });
                    } else if (latLngs.length > 1) {
                        const bounds = L.latLngBounds(latLngs);
                        this.map.flyToBounds(bounds, { padding: [80, 80], maxZoom: 16, duration: 1.2 });
                    }

                    const catTitle = category === 'kuliner' ? 'Kuliner Khas' : (category === 'oleh_oleh' ? 'Pusat Oleh-Oleh' : 'Spot Foto');
                    this.showToast('success', `${catTitle} (${matchingSpots.length} Spot)`, 'Peta diarahkan ke lokasi spot. Klik pin di peta untuk melihat deskripsi lengkap!');
                },

                renderDestinationPins() {
                    if (!this.destinationMarkersLayer || !this.map) return;

                    this.destinationMarkersLayer.clearLayers();

                    this.destinations.forEach(dest => {
                        if (!dest.latitude || !dest.longitude) return;

                        const isSelected = this.selectedDestination && this.selectedDestination.id === dest.id;

                        // Custom HTML Pin Marker with Dark Label
                        const iconHtml = `
                            <div style="width: 180px; text-align: center; cursor: pointer;" class="group">
                                <div style="width: 42px; height: 42px; border-radius: 14px; background: linear-gradient(135deg, #0284c7, #4f46e5); color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3); border: 2.5px solid #ffffff; margin: 0 auto; transition: transform 0.2s; ${isSelected ? 'box-shadow: 0 0 0 4px #38bdf8, 0 10px 20px rgba(2,132,199,0.5); transform: scale(1.15);' : ''}">
                                    <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div style="display: inline-block; margin-top: 4px; background-color: #0f172a; color: #ffffff; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); border: 1.5px solid #334155; white-space: nowrap; max-width: 170px; overflow: hidden; text-overflow: ellipsis; letter-spacing: -0.01em;">
                                    ${dest.name}
                                </div>
                            </div>
                        `;

                        const customIcon = L.divIcon({
                            html: iconHtml,
                            className: 'custom-div-icon',
                            iconSize: [180, 70],
                            iconAnchor: [90, 21]
                        });

                        const marker = L.marker([parseFloat(dest.latitude), parseFloat(dest.longitude)], { icon: customIcon });

                        marker.on('click', () => {
                            this.selectDestination(dest);
                        });

                        this.destinationMarkersLayer.addLayer(marker);
                    });
                },

                selectDestination(dest) {
                    this.selectedDestination = dest;
                    this.selectedSpot = null;

                    this.renderDestinationPins();

                    if (this.map) {
                        this.map.flyTo([parseFloat(dest.latitude), parseFloat(dest.longitude)], 15, {
                            duration: 1.2
                        });

                        if (this.radiusCircleLayer) {
                            this.map.removeLayer(this.radiusCircleLayer);
                        }
                        this.radiusCircleLayer = L.circle([parseFloat(dest.latitude), parseFloat(dest.longitude)], {
                            color: '#0284c7',
                            fillColor: '#38bdf8',
                            fillOpacity: 0.15,
                            radius: 1200,
                            weight: 2,
                            dashArray: '5, 8'
                        }).addTo(this.map);
                    }

                    this.renderSpotPins(dest);
                },

                closeDestination() {
                    this.selectedDestination = null;
                    this.selectedSpot = null;
                    if (this.spotsMarkersLayer) {
                        this.spotsMarkersLayer.clearLayers();
                    }
                    if (this.radiusCircleLayer && this.map) {
                        this.map.removeLayer(this.radiusCircleLayer);
                        this.radiusCircleLayer = null;
                    }
                    this.renderDestinationPins();
                },

                renderSpotPins(dest) {
                    if (!this.spotsMarkersLayer || !this.map) return;

                    this.spotsMarkersLayer.clearLayers();

                    const spots = this.filteredSpots;
                    if (!spots || spots.length === 0) return;

                    spots.forEach(spot => {
                        this.addSpotMarker(spot);
                    });
                },

                addSpotMarker(spot) {
                    if (!spot.latitude || !spot.longitude) return;

                    let colorGradient = 'linear-gradient(135deg, #f59e0b, #ea580c)';
                    let borderAccent = '#f59e0b';
                    let iconSvg = '<svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>';

                    if (spot.category === 'oleh_oleh') {
                        colorGradient = 'linear-gradient(135deg, #10b981, #0d9488)';
                        borderAccent = '#10b981';
                        iconSvg = '<svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>';
                    } else if (spot.category === 'spot_foto') {
                        colorGradient = 'linear-gradient(135deg, #a855f7, #db2777)';
                        borderAccent = '#a855f7';
                        iconSvg = '<svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>';
                    }

                    const isCheckedIn = spot.is_checked_in || this.checkedInSpotIds.includes(spot.id);

                    // Dark label with colored accent border for maximum legibility
                    const spotHtml = `
                        <div style="width: 170px; text-align: center; cursor: pointer;" class="group">
                            <div style="width: 34px; height: 34px; border-radius: 9999px; background: ${colorGradient}; color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 12px rgba(0,0,0,0.3); border: 2px solid #ffffff; margin: 0 auto; transition: transform 0.2s;" class="group-hover:scale-110">
                                ${iconSvg}
                            </div>
                            <div style="display: inline-block; margin-top: 3px; background-color: #0f172a; color: #ffffff; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.4); border: 1.5px solid ${borderAccent}; white-space: nowrap; max-width: 160px; overflow: hidden; text-overflow: ellipsis;">
                                ${isCheckedIn ? '<span style="color:#34d399; margin-right:2px; font-weight:900;">✓</span>' : ''}${spot.name}
                            </div>
                        </div>
                    `;

                    const spotIcon = L.divIcon({
                        html: spotHtml,
                        className: 'custom-div-icon',
                        iconSize: [170, 60],
                        iconAnchor: [85, 17]
                    });

                    const marker = L.marker([parseFloat(spot.latitude), parseFloat(spot.longitude)], { icon: spotIcon });

                    marker.on('click', () => {
                        this.selectedSpot = spot;
                        this.selectedSpot.is_checked_in = isCheckedIn;
                        if (this.map) {
                            this.map.flyTo([parseFloat(spot.latitude), parseFloat(spot.longitude)], 16, { duration: 0.8 });
                        }
                    });

                    this.spotsMarkersLayer.addLayer(marker);
                },

                claimCheckin(spot) {
                    if (!this.isAuthenticated) {
                        this.showToast('error', 'Perlu Masuk Akun', 'Silakan login terlebih dahulu untuk melakukan check-in dan mengklaim XP.');
                        setTimeout(() => {
                            window.location.href = '{{ route("login") }}';
                        }, 1500);
                        return;
                    }

                    this.claiming = true;

                    fetch(`/tripy/spots/${spot.id}/checkin`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.claiming = false;
                        if (data.success) {
                            spot.is_checked_in = true;
                            if (!this.checkedInSpotIds.includes(spot.id)) {
                                this.checkedInSpotIds.push(spot.id);
                            }
                            this.userPoints = data.total_points;
                            this.showToast('success', 'Check-in Berhasil!', data.message);
                            this.renderSpotPins(this.selectedDestination);
                        } else {
                            this.showToast('error', 'Gagal Check-in', data.message);
                        }
                    })
                    .catch(err => {
                        this.claiming = false;
                        console.error(err);
                        this.showToast('error', 'Kesalahan Server', 'Terjadi kendala saat melakukan check-in.');
                    });
                },

                showToast(type, title, message) {
                    this.toast = {
                        show: true,
                        type: type,
                        title: title,
                        message: message
                    };
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 4000);
                }
            };
        }
    </script>

    <!-- TRIPY Container with Alpine.js Map Controller -->
    <div
        x-data="tripyExplorer({
            cities: {{ Js::from($cities) }},
            activeCity: {{ Js::from($activeCity) }},
            destinations: {{ Js::from($destinations) }},
            checkedInSpotIds: {{ Js::from($checkedInSpotIds) }},
            isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
            userPoints: {{ auth()->user()->reputation_points ?? 0 }},
            csrfToken: '{{ csrf_token() }}'
        })"
        x-init="initMap()"
        class="relative w-full h-[calc(100vh-4rem)] lg:h-screen flex flex-col overflow-hidden bg-slate-900"
        style="height: 100vh; min-height: 500px;"
    >
        <!-- Floating Header Controls (City Bar & Filter Toggles) -->
        <div class="absolute top-4 inset-x-4 flex flex-wrap items-center justify-between gap-3 pointer-events-auto" style="z-index: 2000;">
            <!-- Left: City Selector Chips -->
            <div id="cities" class="bg-white/95 backdrop-blur-md px-3 py-2 rounded-2xl shadow-lg border border-slate-200/90 flex items-center gap-2 max-w-full overflow-x-auto custom-scrollbar scroll-mt-24">
                <div class="flex items-center gap-1.5 px-2 text-xs font-black text-slate-800 shrink-0">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                    <span>KOTA:</span>
                </div>
                <template x-for="city in cities" :key="city.id">
                    <button
                        @click="switchCity(city)"
                        type="button"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer shadow-xs hover:scale-105 active:scale-95"
                        :class="activeCity && activeCity.id === city.id
                            ? 'bg-sky-600 text-white shadow-md shadow-sky-600/40 ring-2 ring-sky-300'
                            : 'bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-slate-900'"
                    >
                        <span x-text="city.name"></span>
                    </button>
                </template>
            </div>

            <!-- Right: Filter Badges & XP Pill -->
            <div class="flex items-center gap-2">
                <!-- Category Filter Pills -->
                <div id="spots" class="flex bg-white/95 backdrop-blur-md p-1.5 rounded-2xl shadow-lg border border-slate-200/90 items-center gap-1.5 text-xs scroll-mt-24">
                    <button
                        @click="setCategoryFilter('all')"
                        type="button"
                        class="px-3 py-1.5 rounded-xl font-bold transition-all cursor-pointer shadow-xs hover:scale-105 active:scale-95"
                        :class="activeCategoryFilter === 'all' ? 'bg-slate-900 text-white shadow ring-2 ring-slate-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                    >
                        Semua
                    </button>
                    <button
                        @click="setCategoryFilter('kuliner')"
                        type="button"
                        class="px-3 py-1.5 rounded-xl font-bold flex items-center gap-1.5 transition-all cursor-pointer shadow-xs hover:scale-105 active:scale-95"
                        :class="activeCategoryFilter === 'kuliner' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/30 ring-2 ring-amber-300' : 'text-amber-800 bg-amber-50/80 hover:bg-amber-100'"
                    >
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 border border-white"></span>
                        <span>Kuliner</span>
                    </button>
                    <button
                        @click="setCategoryFilter('oleh_oleh')"
                        type="button"
                        class="px-3 py-1.5 rounded-xl font-bold flex items-center gap-1.5 transition-all cursor-pointer shadow-xs hover:scale-105 active:scale-95"
                        :class="activeCategoryFilter === 'oleh_oleh' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30 ring-2 ring-emerald-300' : 'text-emerald-800 bg-emerald-50/80 hover:bg-emerald-100'"
                    >
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 border border-white"></span>
                        <span>Oleh-Oleh</span>
                    </button>
                    <button
                        @click="setCategoryFilter('spot_foto')"
                        type="button"
                        class="px-3 py-1.5 rounded-xl font-bold flex items-center gap-1.5 transition-all cursor-pointer shadow-xs hover:scale-105 active:scale-95"
                        :class="activeCategoryFilter === 'spot_foto' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/30 ring-2 ring-purple-300' : 'text-purple-800 bg-purple-50/80 hover:bg-purple-100'"
                    >
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-400 border border-white"></span>
                        <span>Spot Foto</span>
                    </button>
                </div>

                <!-- XP Gamification Pill (Interactive Check-in History Trigger) -->
                <button
                    id="checkin"
                    type="button"
                    @click="showCheckinModal = true"
                    class="bg-slate-900/90 hover:bg-slate-800 backdrop-blur-md text-white px-4 py-2 rounded-2xl shadow-lg border border-slate-700/80 flex items-center gap-2 scroll-mt-24 transition-all cursor-pointer select-none group"
                    title="Buka Riwayat Check-in & XP"
                >
                    <div class="w-6 h-6 rounded-lg bg-amber-400/20 text-amber-300 group-hover:scale-110 flex items-center justify-center font-bold text-xs transition-transform">
                        ★
                    </div>
                    <div class="text-left">
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">XP Penjelajah</div>
                        <div class="text-xs font-black text-amber-300">
                            <span x-text="userPoints"></span> XP
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Full Map Container with Guaranteed Viewport Dimensions -->
        <div id="tripy-map" class="w-full h-full z-10" style="min-height: 100vh; width: 100%; height: 100%;"></div>

        <!-- Notification Toast -->
        <div
            x-show="toast.show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            x-cloak
            class="fixed bottom-24 right-6 z-[1200] max-w-sm bg-slate-900/95 backdrop-blur-md text-white p-4 rounded-2xl shadow-2xl border border-slate-700 flex items-start gap-3"
        >
            <div
                class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                :class="toast.type === 'success' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'"
            >
                <template x-if="toast.type === 'success'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </template>
                <template x-if="toast.type !== 'success'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </template>
            </div>
            <div class="flex-1">
                <div class="text-xs font-bold" x-text="toast.title"></div>
                <div class="text-[11px] text-slate-300 mt-0.5" x-text="toast.message"></div>
            </div>
            <button @click="toast.show = false" class="text-slate-400 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Bottom Sheet / Floating Card for Selected Destination -->
        <div
            x-show="selectedDestination"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-8"
            class="absolute bottom-6 left-6 right-6 md:right-auto md:max-w-md bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden"
            style="z-index: 2000;"
        >
            <template x-if="selectedDestination">
                <div>
                    <!-- Photo Header -->
                    <div class="relative h-44 w-full bg-slate-800 overflow-hidden">
                        <img
                            :src="selectedDestination.cover_image || 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=800&q=80'"
                            :alt="selectedDestination.name"
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>

                        <!-- Close Button -->
                        <button
                            @click="closeDestination()"
                            type="button"
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition-colors cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Badges -->
                        <div class="absolute bottom-3 left-4 flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-lg bg-sky-500 text-white text-[10px] font-black uppercase tracking-wider shadow-sm" x-text="selectedDestination.type || 'Landmark'"></span>
                            <span class="px-2.5 py-1 rounded-lg bg-white/90 text-slate-800 text-[10px] font-bold shadow-sm" x-text="selectedDestination.category || 'Budaya'"></span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5 space-y-4">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight leading-snug" x-text="selectedDestination.name"></h3>
                            <p class="text-xs text-slate-600 mt-1.5 leading-relaxed" x-text="selectedDestination.short_description || selectedDestination.description"></p>
                        </div>

                        <!-- Active Radius Spots Indicator -->
                        <div class="bg-sky-50 border border-sky-100 rounded-2xl p-3 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-sky-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-sky-900">Rekomendasi Sekitar Aktif</div>
                                    <div class="text-[11px] text-sky-700">
                                        <span x-text="filteredSpots.length"></span> spot kuliner & oleh-oleh di peta
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 pt-1">
                            <a
                                :href="selectedDestination.url || ('/destinations/' + selectedDestination.slug)"
                                class="flex-1 text-center bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition-colors"
                            >
                                Lihat Direktori Lengkap &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Spot Detail Modal / Bottom Drawer (When Spot Pin is Clicked) -->
        <div
            x-show="selectedSpot"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-8"
            class="absolute bottom-6 right-6 left-6 md:left-auto md:w-96 bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-slate-200 overflow-hidden"
            style="z-index: 2100;"
        >
            <template x-if="selectedSpot">
                <div>
                    <!-- Spot Header -->
                    <div class="relative h-36 bg-slate-800 overflow-hidden">
                        <img
                            :src="selectedSpot.photo_url || 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=600&q=80'"
                            :alt="selectedSpot.name"
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>

                        <button
                            @click="selectedSpot = null"
                            type="button"
                            class="absolute top-3 right-3 w-7 h-7 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition-colors cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="absolute bottom-3 left-4 flex items-center gap-2">
                            <span
                                class="px-2.5 py-1 rounded-lg text-white text-[10px] font-black uppercase tracking-wider"
                                :class="selectedSpot.category === 'kuliner' ? 'bg-amber-500' : (selectedSpot.category === 'oleh_oleh' ? 'bg-emerald-500' : 'bg-purple-500')"
                                x-text="selectedSpot.category === 'kuliner' ? 'Kuliner Khas' : (selectedSpot.category === 'oleh_oleh' ? 'Pusat Oleh-Oleh' : 'Spot Foto')"
                            ></span>
                            <span class="px-2 py-0.5 rounded-lg bg-amber-400 text-slate-900 font-extrabold text-[10px]">
                                +<span x-text="selectedSpot.points_reward || 10"></span> XP
                            </span>
                        </div>
                    </div>

                    <!-- Spot Body -->
                    <div class="p-4 space-y-3">
                        <div>
                            <h4 class="text-base font-black text-slate-900 leading-tight" x-text="selectedSpot.name"></h4>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed" x-text="selectedSpot.short_description"></p>
                        </div>

                        <!-- Check-in & Claim XP CTA -->
                        <div class="pt-2">
                            <template x-if="selectedSpot.is_checked_in">
                                <div class="w-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-xs py-2.5 px-4 rounded-xl flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Sudah Check-in & XP Terklaim ✓</span>
                                </div>
                            </template>

                            <template x-if="!selectedSpot.is_checked_in">
                                <button
                                    @click="claimCheckin(selectedSpot)"
                                    type="button"
                                    :disabled="claiming"
                                    class="w-full bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-700 hover:to-indigo-700 text-white font-black text-xs py-3 px-4 rounded-xl shadow-lg shadow-sky-600/25 flex items-center justify-center gap-2 transition-all active:scale-[0.98] cursor-pointer disabled:opacity-50"
                                >
                                    <template x-if="claiming">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    </template>
                                    <template x-if="!claiming">
                                        <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/></svg>
                                    </template>
                                    <span>Check-in & Claim XP (+<span x-text="selectedSpot.points_reward || 10"></span> Poin)</span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Modal Dialog Riwayat Check-in & XP Penjelajah -->
        <div
            x-show="showCheckinModal"
            x-cloak
            class="fixed inset-0 z-[2500] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
        >
            <div
                @click.away="closeCheckinModal()"
                x-show="showCheckinModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200 select-none"
            >
                <!-- Modal Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-slate-800 to-sky-950 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-400/20 text-amber-300 flex items-center justify-center font-black text-lg border border-amber-400/30 shadow-inner">
                            ★
                        </div>
                        <div>
                            <h3 class="text-base font-black tracking-tight">Riwayat Check-in & XP</h3>
                            <p class="text-[11px] text-slate-300">Poin petualangan & spot yang kamu jelajahi</p>
                        </div>
                    </div>
                    <button
                        @click="closeCheckinModal()"
                        type="button"
                        class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-white/10 transition-colors cursor-pointer"
                        title="Tutup Modal"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                    <!-- XP Summary Card -->
                    <div class="p-4 bg-gradient-to-br from-amber-50 to-amber-100/60 rounded-2xl border border-amber-200 flex items-center justify-between">
                        <div>
                            <div class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Total Akumulasi Poin</div>
                            <div class="text-2xl font-black text-amber-600 mt-0.5">
                                <span x-text="userPoints"></span> XP
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Spot Terjelajahi</div>
                            <div class="text-lg font-black text-slate-900 mt-0.5">
                                <span x-text="checkedInSpotIds.length"></span> Lokasi
                            </div>
                        </div>
                    </div>

                    <!-- Status Konten Check-in -->
                    <template x-if="isAuthenticated">
                        <div class="space-y-2">
                            <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Aktivitas Check-in Kamu</div>
                            <template x-if="checkedInSpotIds.length === 0">
                                <div class="text-center py-6 px-4 text-slate-400 text-xs bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                    Belum ada check-in spot. Pilih salah satu landmark di peta, lalu klaim check-in spot kuliner & oleh-oleh sekitarnya untuk mengumpulkan XP!
                                </div>
                            </template>
                            <template x-if="checkedInSpotIds.length > 0">
                                <div class="p-3.5 bg-emerald-50 rounded-2xl border border-emerald-200 text-xs text-emerald-800 space-y-1">
                                    <div class="font-bold flex items-center gap-1.5 text-emerald-900">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Hebat! Kamu telah check-in di <span x-text="checkedInSpotIds.length"></span> spot</span>
                                    </div>
                                    <p class="text-[11px] text-emerald-700">Poin XP kamu dapat ditukarkan dengan diskon tiket & voucher kuliner di modul Eksplor Pedia.</p>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!isAuthenticated">
                        <div class="text-center py-5 bg-sky-50 rounded-2xl border border-sky-100 p-4 space-y-3">
                            <p class="text-xs text-sky-800 font-semibold leading-relaxed">
                                Masuk ke akun GoHunter untuk menyimpan riwayat check-in spot kuliner, oleh-oleh, dan mengklaim reward voucher wisata!
                            </p>
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-sky-600/30"
                            >
                                Masuk Sekarang &rarr;
                            </a>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400 font-semibold">TRIPY • GoHunter</span>
                    <button
                        @click="closeCheckinModal()"
                        type="button"
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-slate-800 transition-colors cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
