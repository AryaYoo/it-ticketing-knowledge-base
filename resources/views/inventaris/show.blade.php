@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        #map-lantai1,
        #map-lantai2 {
            width: 100%;
            height: 400px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 2px solid white;
            background: #f8f9fa;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            padding: 8px 16px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd !important;
            border-bottom-color: #0d6efd !important;
            background: transparent !important;
        }

        .nav-tabs .nav-link:hover:not(.active) {
            border-bottom-color: #dee2e6;
        }
    </style>

    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('inventaris.index') }}"
                class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center text-muted hover-primary transition-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span class="fw-bold text-uppercase small" style="letter-spacing: 0.1em;">Kembali ke Daftar Aset</span>
            </a>
        </div>

        <div class="row">
            <!-- Asset Details -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">Informasi Asset</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editAssetModal">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Kategori</label>
                            <div class="h6 mb-0">
                                @if($asset->category === 'computer')
                                    <span class="text-primary font-bold">Komputer</span>
                                @else
                                    <span class="text-info font-bold">Non-Komputer</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Nama Asset</label>
                            <div class="h5 mb-0 fw-bold">{{ $asset->display_name }}</div>
                        </div>
                        @if($asset->category === 'computer' && $asset->ipMapping)
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold">IP Address</label>
                                <div class="h6 mb-0"><code>{{ $asset->ipMapping->ip_address }}</code></div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Lokasi</label>
                            <div class="h6 mb-0">{{ $asset->location ?? 'Tidak ada data lokasi' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Status</label>
                            <div>
                                @if($asset->status === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($asset->status === 'broken')
                                    <span class="badge bg-danger">Rusak</span>
                                @else
                                    <span class="badge bg-secondary">Dibuang</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Keterangan</label>
                            <p class="mb-0 small">{{ $asset->description ?? '-' }}</p>
                        </div>

                        @if($asset->remote_app_name || $asset->remote_address)
                            <hr class="my-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-display text-primary me-2"></i>
                                <h6 class="mb-0 fw-bold text-primary">Akses Remote</h6>
                            </div>
                            <div class="bg-light p-2 rounded small">
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted">Aplikasi:</span>
                                    <span class="fw-bold">{{ $asset->remote_app_name ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted">ID/Alamat:</span>
                                    <span class="fw-bold fw-mono">{{ $asset->remote_address ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between pt-1">
                                    <span class="text-muted">Password:</span>
                                    <span>
                                        @if($asset->remote_password)
                                            <span class="user-select-all font-monospace px-1 bg-white border rounded">
                                                {{ $asset->remote_password }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Edit Asset Modal -->
            <div class="modal fade" id="editAssetModal" tabindex="-1" aria-labelledby="editAssetModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-primary text-white border-0">
                            <h5 class="modal-title" id="editAssetModalLabel">Edit Informasi Asset</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('inventaris.update', $asset->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label for="location" class="form-label fw-bold small text-uppercase">Lokasi /
                                        Ruangan</label>
                                    <select class="form-select" id="location" name="location">
                                        <option value="">-- Pilih Lokasi --</option>
                                        @foreach(App\Models\Asset::ZONES as $floor => $zones)
                                            <optgroup label="{{ $floor }}">
                                                @foreach($zones as $zone)
                                                    <option value="{{ $zone }}" {{ old('location', $asset->location) == $zone ? 'selected' : '' }}>
                                                        {{ $zone }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label fw-bold small text-uppercase">Status Asset</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" {{ old('status', $asset->status) === 'active' ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="broken" {{ old('status', $asset->status) === 'broken' ? 'selected' : '' }}>
                                            Rusak</option>
                                        <option value="disposed" {{ old('status', $asset->status) === 'disposed' ? 'selected' : '' }}>
                                            Dibuang / Tidak digunakan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold small text-uppercase">Keterangan /
                                        Spesifikasi</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        placeholder="Tambahkan informasi tambahan...">{{ old('description', $asset->description) }}</textarea>
                                </div>

                                <hr class="my-3">
                                <h6 class="fw-bold text-primary mb-2"><i class="bi bi-display me-1"></i> Informasi Remote
                                    Desktop</h6>

                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="modal_remote_app_name" class="form-label text-muted small">Aplikasi
                                            Remote</label>
                                        <input type="text" class="form-control form-control-sm" id="modal_remote_app_name"
                                            name="remote_app_name"
                                            value="{{ old('remote_app_name', $asset->remote_app_name) }}"
                                            placeholder="Contoh: AnyDesk">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="modal_remote_address" class="form-label text-muted small">ID /
                                            Alamat</label>
                                        <input type="text" class="form-control form-control-sm" id="modal_remote_address"
                                            name="remote_address"
                                            value="{{ old('remote_address', $asset->remote_address) }}"
                                            placeholder="Contoh: 123 456 789">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label for="modal_remote_password"
                                            class="form-label text-muted small">Password</label>
                                        <input type="text" class="form-control form-control-sm" id="modal_remote_password"
                                            name="remote_password"
                                            value="{{ old('remote_password', $asset->remote_password) }}"
                                            placeholder="Password">
                                    </div>
                                </div>

                                {{-- Hidden fields to preserve name if it's non-computer --}}
                                @if($asset->category === 'non-computer')
                                    <input type="hidden" name="name" value="{{ $asset->name }}">
                                @endif
                            </div>
                            <div class="modal-footer bg-light border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Maintenance Sections -->
            <div class="col-md-8">
                <!-- Upcoming Schedule -->
                <div class="card border-0 shadow-sm mb-4 border-start border-warning border-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-warning d-flex align-items-center">
                            <i class="bi bi-calendar-check me-2"></i> Jadwal Maintenance Mendatang
                        </h5>
                        <a href="{{ route('maintenances.create', $asset->id) }}?status=pending"
                            class="btn btn-outline-warning btn-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-plus-lg"></i> Buat Jadwal
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-2 small fw-bold text-muted text-uppercase">Rencana Tanggal</th>
                                        <th class="py-2 small fw-bold text-muted text-uppercase">Kegiatan</th>
                                        <th class="pe-4 py-2 text-end small fw-bold text-muted text-uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingMaintenances as $schedule)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold">{{ $schedule->maintenance_date->format('d M Y') }}</div>
                                                <small class="text-muted">{{ $schedule->maintenance_date->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $schedule->title ?: 'Perawatan Rutin' }}</div>
                                                <div class="small text-muted">{{ Str::limit($schedule->description, 60) }}</div>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <button class="btn btn-sm btn-success rounded-3 px-3" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#completeModal-{{ $schedule->id }}">
                                                    <i class="bi bi-check-circle me-1"></i> Selesaikan
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Completion Modal for each schedule -->
                                        <div class="modal fade" id="completeModal-{{ $schedule->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <form action="{{ route('maintenances.complete', $schedule->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title fw-bold">Penyelesaian Maintenance</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4 text-start">
                                                            <p class="mb-4">Konfirmasi penyelesaian untuk <strong>{{ $asset->display_name }}</strong>.</p>
                                                            <div class="mb-3">
                                                                <label for="desc-{{ $schedule->id }}" class="form-label fw-bold small text-uppercase">Hasil Pekerjaan / Catatan</label>
                                                                <textarea class="form-control" id="desc-{{ $schedule->id }}" name="description" rows="3" required>{{ $schedule->description }}</textarea>
                                                            </div>
                                                            <div class="mb-0">
                                                                <label for="cost-{{ $schedule->id }}" class="form-label fw-bold small text-uppercase">Total Biaya (Rp)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-light">Rp</span>
                                                                    <input type="number" class="form-control" id="cost-{{ $schedule->id }}" name="cost" value="0" min="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light p-3">
                                                            <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-success px-4 fw-bold rounded-3">Simpan & Selesaikan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted small italic">
                                                Tidak ada jadwal maintenance pending.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Maintenance History -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-start border-primary border-4">
                        <h5 class="mb-0 text-primary d-flex align-items-center">
                            <i class="bi bi-clock-history me-2"></i> Riwayat Maintenance
                        </h5>
                        <a href="{{ route('maintenances.create', $asset->id) }}?status=completed"
                            class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-plus-lg"></i> Log Manual
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-2 small fw-bold text-muted text-uppercase">Tanggal</th>
                                        <th class="py-2 small fw-bold text-muted text-uppercase">Keterangan</th>
                                        <th class="py-2 small fw-bold text-muted text-uppercase text-center">Oleh</th>
                                        <th class="pe-4 py-2 text-end small fw-bold text-muted text-uppercase">Biaya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($completedMaintenances as $log)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold">{{ $log->maintenance_date->format('d M Y') }}</div>
                                            </td>
                                            <td>
                                                <div class="small fw-bold text-dark">{{ $log->title ?: 'Perbaikan' }}</div>
                                                <div class="small text-muted">{{ $log->description }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border fw-normal">{{ $log->performedByUser->name ?? 'System' }}</span>
                                            </td>
                                            <td class="pe-4 text-end fw-bold text-success">
                                                Rp {{ number_format($log->cost, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-info-circle fs-1 d-block mb-3 opacity-25"></i>
                                                Belum ada riwayat maintenance untuk aset ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($completedMaintenances->hasPages())
                            <div class="card-footer bg-white border-top py-3">
                                {{ $completedMaintenances->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                            <h5 class="fw-bold text-primary mb-0 d-flex align-items-center">
                                <i class="bi bi-map-fill me-2"></i> Zona Lokasi Aset IT RSIA IBI
                            </h5>
                        </div>
                        <ul class="nav nav-tabs border-0 px-2" id="floorTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold border-0 border-bottom border-3 border-transparent"
                                    id="lantai1-tab" data-bs-toggle="tab" data-bs-target="#lantai1" type="button"
                                    role="tab">
                                    <i class="bi bi-layers me-1"></i> Lantai 1
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold border-0 border-bottom border-3 border-transparent"
                                    id="lantai2-tab" data-bs-toggle="tab" data-bs-target="#lantai2" type="button"
                                    role="tab">
                                    <i class="bi bi-layers-fill me-1"></i> Lantai 2
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="floorTabsContent">
                            <div class="tab-pane fade show active" id="lantai1" role="tabpanel">
                                <div id="map-lantai1"></div>
                            </div>
                            <div class="tab-pane fade" id="lantai2" role="tabpanel">
                                <div id="map-lantai2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Konfigurasi Peta Lantai 1
            var mapLantai1 = L.map('map-lantai1').setView([-7.2453, 112.7276], 20);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 22,
                maxNativeZoom: 19,
                attribution: 'Hospital Map'
            }).addTo(mapLantai1);

            // Konfigurasi Peta Lantai 2
            var mapLantai2 = L.map('map-lantai2').setView([-7.2453, 112.7276], 20);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 22,
                maxNativeZoom: 19,
                attribution: 'Hospital Map'
            }).addTo(mapLantai2);

            // Data dari GeoJSON Lantai 1
            var hospitalDataL1 = {
                "type": "FeatureCollection",
                "features": [
                    { "type": "Feature", "properties": { "name": "Zona A" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7274589, -7.2453605], [112.7275034, -7.2453611], [112.7275047, -7.2454054], [112.7274597, -7.2454058], [112.7274589, -7.2453605]]] } },
                    { "type": "Feature", "properties": { "name": "Zona B" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7275144, -7.2453157], [112.7275155, -7.2453597], [112.7275322, -7.2453597], [112.7275303, -7.2453165], [112.7275144, -7.2453157]]] } },
                    { "type": "Feature", "properties": { "name": "Zona C" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7276069, -7.245316], [112.7275646, -7.2453157], [112.7275649, -7.2453605], [112.7276072, -7.2453602], [112.7276069, -7.245316]]] } },
                    { "type": "Feature", "properties": { "name": "Zona D" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7275478, -7.2453163], [112.7275472, -7.2453401], [112.7275629, -7.2453401], [112.7275626, -7.2453163], [112.7275478, -7.2453163]]] } },
                    { "type": "Feature", "properties": { "name": "Zona E" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7276091, -7.2453729], [112.7276081, -7.245389], [112.7276385, -7.2453906], [112.7276395, -7.2453729], [112.7276091, -7.2453729]]] } },
                    { "type": "Feature", "properties": { "name": "Zona F" }, "geometry": { "type": "Polygon", "coordinates": [[[112.727502, -7.2453033], [112.7275031, -7.2453609], [112.7274813, -7.2453603], [112.727482, -7.2453033], [112.727502, -7.2453033]]] } },
                    { "type": "Feature", "properties": { "name": "Zona G" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7274794, -7.2452707], [112.7274794, -7.245292], [112.7274718, -7.2452922], [112.7274712, -7.2453217], [112.7274593, -7.2453213], [112.7274587, -7.2452701], [112.7274794, -7.2452707]]] } },
                    { "type": "Feature", "properties": { "name": "Zona H" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7275201, -7.2452583], [112.7275199, -7.2452691], [112.7275259, -7.245269], [112.7275256, -7.2452578], [112.7275201, -7.2452583]]] } },
                    { "type": "Feature", "properties": { "name": "Zona I" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7275447, -7.2452661], [112.7275447, -7.2452688], [112.7275539, -7.2452689], [112.7275537, -7.2452661], [112.7275447, -7.2452661]]] } },
                    { "type": "Feature", "properties": { "name": "Zona J" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7275561, -7.2451988], [112.7275591, -7.2452221], [112.7275944, -7.2452165], [112.7275907, -7.2451937], [112.7275561, -7.2451988]]] } },
                    { "type": "Feature", "properties": { "name": "Zona K" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7275632, -7.2452688], [112.7275643, -7.2452933], [112.7276077, -7.245293], [112.7276074, -7.2452688], [112.7275632, -7.2452688]]] } },
                    { "type": "Feature", "properties": { "name": "Zona L" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7276359, -7.2452717], [112.7276243, -7.2452719], [112.7276235, -7.245295], [112.7276361, -7.2452952], [112.7276359, -7.2452717]]] } },
                    { "type": "Feature", "properties": { "name": "Zona M" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7278277, -7.2453123], [112.727826, -7.245357], [112.7277629, -7.2453561], [112.7277625, -7.2453118], [112.7278277, -7.2453123]]] } },
                    { "type": "Feature", "properties": { "name": "Zona N" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7277109, -7.2453678], [112.7277109, -7.2453908], [112.7276969, -7.2453908], [112.7276969, -7.245367], [112.7277109, -7.2453678]]] } },
                    { "type": "Feature", "properties": { "name": "Zona O" }, "geometry": { "type": "Polygon", "coordinates": [[[112.7279431, -7.2453275], [112.7279247, -7.2453282], [112.727924, -7.2453581], [112.7279456, -7.2453578], [112.7279431, -7.2453275]]] } }
                ]
            };

            // Data GeoJSON Lantai 2
            var hospitalDataL2 = {
                "type": "FeatureCollection",
                "features": [
                    {
                        "type": "Feature",
                        "properties": { "name": "Zona 2A" },
                        "geometry": { "type": "Polygon", "coordinates": [[[112.7274582, -7.2453537], [112.7276067, -7.2453537], [112.7276062, -7.2454051], [112.7274592, -7.2454051], [112.7274582, -7.2453537]]] }
                    },
                    {
                        "type": "Feature",
                        "properties": { "name": "Zona 2B" },
                        "geometry": { "type": "Polygon", "coordinates": [[[112.7276078, -7.2453537], [112.7276559, -7.2453585], [112.7276548, -7.245382], [112.7276067, -7.2453768], [112.7276078, -7.2453537]]] }
                    },
                    {
                        "type": "Feature",
                        "properties": { "name": "Zona 2C" },
                        "geometry": { "type": "Polygon", "coordinates": [[[112.7279878, -7.245262], [112.7279223, -7.2452635], [112.7279239, -7.2452961], [112.7279878, -7.2452945], [112.7279878, -7.245262]]] }
                    }
                ]
            };

            var assetLocation = @json($asset->location);
            var matchedLayer = null;
            var matchedFloor = null;

            // Fungsi Helper untuk merender GeoJSON ke peta
            function renderGeoJSON(map, data, floorId) {
                if (!data || !data.features || data.features.length === 0) return null;

                var layer = L.geoJSON(data, {
                    style: function (feature) {
                        var isMatch = assetLocation && feature.properties.name && feature.properties.name.toLowerCase() === assetLocation.toLowerCase();

                        if (isMatch) {
                            return {
                                fillColor: "#e74c3c", // Warna merah muda/vibrant jika cocok
                                color: "#c0392b",     // Border merah gelap
                                weight: 3,
                                fillOpacity: 0.8
                            };
                        }

                        return {
                            fillColor: "#3498db",
                            color: "#2980b9",
                            weight: 2,
                            fillOpacity: 0.6
                        };
                    },
                    onEachFeature: function (feature, layer) {
                        var popupContent = "<b>" + (feature.properties.name || "Ruangan") + "</b>";
                        var isMatch = assetLocation && feature.properties.name && feature.properties.name.toLowerCase() === assetLocation.toLowerCase();

                        if (isMatch) {
                            popupContent += "<br><span class='badge bg-danger mt-1'><i class='bi bi-geo-alt-fill'></i> Lokasi Aset Ini</span>";
                            matchedLayer = layer;
                            matchedFloor = floorId;
                        }

                        layer.bindPopup(popupContent);
                    }
                }).addTo(map);

                return layer;
            }

            // Render Data
            var layerL1 = renderGeoJSON(mapLantai1, hospitalDataL1, 'lantai1');
            var layerL2 = renderGeoJSON(mapLantai2, hospitalDataL2, 'lantai2');

            // Handle focus dan tab switching berdasarkan kecocokan lokasi
            if (matchedLayer && matchedFloor) {
                // Switch tab otomatis ke lantai yang benar
                var tabId = matchedFloor === 'lantai1' ? 'lantai1-tab' : 'lantai2-tab';
                var tabEl = document.querySelector('#' + tabId);
                if (tabEl) {
                    var tab = new bootstrap.Tab(tabEl);
                    tab.show();
                }

                // Fokus (zoom) ke layer yang cocok
                var mapToFocus = matchedFloor === 'lantai1' ? mapLantai1 : mapLantai2;

                // Gunakan timeout sedikit agar tab selesai dirender sebelum peta disesuaikan (invalidateSize)
                setTimeout(function () {
                    mapToFocus.invalidateSize();
                    mapToFocus.fitBounds(matchedLayer.getBounds(), { padding: [50, 50], maxZoom: 21 });
                    matchedLayer.openPopup();
                }, 300);

            } else {
                // Perilaku default: pusatkan (center) pada Lantai 1 dengan batas keseluruhan
                if (layerL1) {
                    var bounds = layerL1.getBounds();
                    if (bounds.isValid()) {
                        mapLantai1.fitBounds(bounds, { padding: [50, 50] });
                    }
                }
            }

            // Fix Render Error saat ganti tab
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tabEl => {
                tabEl.addEventListener('shown.bs.tab', event => {
                    if (event.target.id === 'lantai1-tab') {
                        mapLantai1.invalidateSize();
                    } else if (event.target.id === 'lantai2-tab') {
                        mapLantai2.invalidateSize();
                    }
                });
            });
        </script>
    </div>
@endsection