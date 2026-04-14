@extends('layouts.app')

@section('content')
    <style>
        /* Custom Styling untuk tombol aksi dengan teks */
        .btn-action-text {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
            white-space: nowrap;
        }

        /* Warna Lembut (Soft Colors) */
        .btn-soft-info {
            background-color: #004b7eff;
            color: #ffffffff;
        }

        .btn-soft-info:hover {
            background-color: #0369a1;
            color: white;
        }

        .btn-soft-primary {
            background-color: #00681fff;
            color: #ffffffff;
        }

        .btn-soft-primary:hover {
            background-color: #05b94aff;
            color: white;
        }

        .btn-soft-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .btn-soft-warning:hover {
            background-color: #92400e;
            color: white;
        }

        .btn-soft-danger {
            background-color: #b91c1c;
            color: #ffffffff;
        }

        .btn-soft-danger:hover {
            background-color: #b91c1c;
            color: white;
        }

        /* Pengaturan Tabel agar tombol tidak turun ke bawah (wrap) */
        .action-column {
            min-width: 320px !important;
            /* Memberi ruang untuk teks tombol */
        }
    </style>

    <div class="container py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-4">
                <h2 class="fw-bold text-primary mb-1">Aset & Inventaris</h2>
                <p class="text-muted mb-0">Kelola pendataan Asset dan maintenance komputer rumah sakit.</p>
            </div>
            <div class="col-md-8 text-md-end mt-3 mt-md-0">
                <div class="d-flex justify-content-md-end flex-wrap gap-2">
                    <!-- Export Button -->
                    <button type="button" class="btn btn-outline-success px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#exportAssetModal">
                        <i class="bi bi-printer fs-5"></i>
                        Cetak Inventaris
                    </button>

                    <a href="{{ route('inventaris.create') }}"
                        class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm rounded-3">
                        <i class="bi bi-plus-lg fs-5"></i>
                        Tambah Asset Non-Komputer
                    </a>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <form action="{{ route('inventaris.index') }}" method="GET" class="d-flex gap-2" style="max-width: 400px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-2 border-end-0 pe-0">

                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-2 border-start-0 bg-white ps-2"
                        placeholder="{{ __('Cari nama, lokasi, atau IP...') }}" value="{{ $search ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary px-3 fw-bold">{{ __('Cari') }}</button>
                @if($search ?? false)
                    <a href="{{ route('inventaris.index') }}"
                        class="btn btn-outline-secondary px-3 fw-bold">{{ __('Clear') }}</a>
                @endif
            </form>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Nama Asset</th>
                            <th class="py-3">Lokasi</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center">Maintenance terakhir</th>
                            <th class="pe-4 py-3 text-end action-column">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $asset->display_name }}</div>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        @if($asset->category === 'computer')
                                            <span class="badge bg-primary bg-opacity-10 text-white border-0">
                                                <i class="bi bi-laptop me-1"></i> Komputer
                                            </span>
                                        @else
                                            <span class="badge bg-info bg-opacity-10 text-info border-0">
                                                <i class="bi bi-tools me-1"></i> Non-Komputer
                                            </span>
                                        @endif

                                        @if($asset->remote_app_name || $asset->remote_address)
                                            <span class="badge bg-success bg-opacity-10 text-success border-0 ms-1"
                                                title="Tersedia untuk Remote">
                                                <i class="bi bi-broadcast me-1"></i> Remote
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $asset->location ?? '-' }}</td>
                                <td class="text-center">
                                    @if($asset->status === 'active')
                                        <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                    @elseif($asset->status === 'broken')
                                        <span class="badge rounded-pill bg-danger px-3">Rusak</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary px-3">Dibuang</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php $lastMaintenance = $asset->maintenances()->latest('maintenance_date')->first(); @endphp
                                    @if($lastMaintenance)
                                        <span
                                            class="text-muted small">{{ $lastMaintenance->maintenance_date->format('d M Y') }}</span>
                                    @else
                                        <span class="text-muted small italic text-opacity-50">Belum ada data</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        {{-- Barcode --}}
                                        <button type="button" class="btn-action-text btn-soft-info"
                                            onclick="showBarcode('{{ $asset->id }}', '{{ $asset->display_name }}')">
                                            <i class="bi bi-qr-code"></i> Barcode
                                        </button>

                                        {{-- Detail --}}
                                        <a href="{{ route('inventaris.show', $asset->id) }}"
                                            class="btn-action-text btn-soft-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>

                                        @if($asset->category === 'non-computer')
                                            {{-- Edit --}}
                                            <a href="{{ route('inventaris.edit', $asset->id) }}"
                                                class="btn-action-text btn-soft-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                        @endif

                                        {{-- Hapus --}}
                                        <form action="{{ route('inventaris.destroy', $asset->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-action-text btn-soft-danger confirm-delete"
                                                data-confirm="Hapus asset ini?">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    Belum ada data asset yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($assets->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $assets->links() }}
                </div>
            @endif
        </div>

        <div class="modal fade" id="barcodeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title small fw-bold text-uppercase">Scan Barcode</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div id="qrcode-container" class="d-flex justify-content-center mb-3 p-3 bg-white border rounded">
                        </div>
                        <div class="fw-bold mb-1 text-primary" id="asset-name-label"></div>
                        <small class="text-muted d-block">Scan untuk update maintenance</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Asset Modal -->
        <div class="modal fade" id="exportAssetModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Pilih Format Laporan Asset</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4 small">Laporan akan mencakup daftar asset sesuai dengan pencarian yang sedang aktif.</p>
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ route('inventaris.export.pdf', ['search' => $search ?? '']) }}" 
                                    class="btn btn-outline-danger w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-3 text-decoration-none">
                                    <i class="bi bi-file-earmark-pdf fs-1"></i>
                                    <span class="fw-bold">Format PDF</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('inventaris.export.excel', ['search' => $search ?? '']) }}" 
                                    class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-3 text-decoration-none">
                                    <i class="bi bi-file-earmark-excel fs-1"></i>
                                    <span class="fw-bold">Format EXCEL</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
        <script>
            function showBarcode(id, name) {
                const modalElement = document.getElementById('barcodeModal');
                const modal = new bootstrap.Modal(modalElement);
                const container = document.getElementById('qrcode-container');
                const label = document.getElementById('asset-name-label');

                container.innerHTML = '';
                label.textContent = name;

                const url = "{{ url('/maintenances/create') }}/" + id;

                new QRCode(container, {
                    text: url,
                    width: 160,
                    height: 160,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });

                modal.show();
            }
        </script>
    </div>
@endsection