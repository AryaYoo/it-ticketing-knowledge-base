@extends('layouts.app')

@section('content')
    <div class="container py-1">
        <div class="d-flex justify-content-between align-items-center mb-4 text-white p-4 rounded-4 shadow-sm"
            style="background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));">
            <div>
                <h2 class="mb-1 fw-bold text-white">Jadwal & Riwayat Maintenance</h2>
                <p class="mb-0 opacity-75">Kelola jadwal perawatan rutin dan pantau riwayat perbaikan aset.</p>
            </div>
            <a href="{{ route('maintenances.create') }}" class="btn btn-light fw-bold px-4 shadow-sm rounded-3">
                <i class="bi bi-plus-lg me-1"></i> Buat Jadwal Baru
            </a>
        </div>

        <div class="row">
            <!-- Pending Schedules -->
            <div class="col-lg-12 mb-5">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-event fs-4 text-primary"></i>
                        <h5 class="mb-0 fw-bold">Jadwal Mendatang (Pending)</h5>
                        <span class="badge bg-primary rounded-pill ms-auto">{{ $pendingMaintenances->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-uppercase small fw-bold text-muted">
                                    <tr>
                                        <th class="ps-4 py-3">Rencana Tanggal</th>
                                        <th>Aset</th>
                                        <th>Kegiatan / Rencana</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($pendingMaintenances->count() > 0)
                                        @foreach($pendingMaintenances as $maintenance)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold">{{ $maintenance->maintenance_date->format('d M Y') }}</div>
                                                    <small class="text-muted">{{ $maintenance->maintenance_date->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('inventaris.show', $maintenance->asset_id) }}" class="text-decoration-none fw-bold text-primary">
                                                        {{ $maintenance->asset->display_name }}
                                                    </a>
                                                    <div class="small text-muted">{{ ucfirst($maintenance->asset->category) }}</div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $maintenance->title ?: 'Tanpa Judul' }}</div>
                                                    <div class="small text-muted text-truncate" style="max-width: 300px;">{{ $maintenance->description }}</div>
                                                </td>
                                                <td class="text-center pe-4">
                                                    <div class="btn-group gap-2">
                                                        <button class="btn btn-sm btn-success rounded-3 px-3" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#completeModal-{{ $maintenance->id }}">
                                                            <i class="bi bi-check-circle me-1"></i> Selesai
                                                        </button>
                                                        <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 confirm-delete" title="Hapus Jadwal">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <!-- Completion Modal -->
                                                    <div class="modal fade" id="completeModal-{{ $maintenance->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow-lg">
                                                                <form action="{{ route('maintenances.complete', $maintenance->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-header bg-success text-white">
                                                                        <h5 class="modal-title fw-bold">Penyelesaian Maintenance</h5>
                                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body p-4 text-start">
                                                                        <p class="mb-4">Konfirmasi penyelesaian untuk <strong>{{ $maintenance->asset->display_name }}</strong>.</p>
                                                                        <div class="mb-3">
                                                                            <label for="description-{{ $maintenance->id }}" class="form-label fw-bold small text-uppercase">Hasil Pekerjaan / Catatan</label>
                                                                            <textarea class="form-control" id="description-{{ $maintenance->id }}" name="description" rows="3" required>{{ $maintenance->description }}</textarea>
                                                                        </div>
                                                                        <div class="mb-0">
                                                                            <label for="cost-{{ $maintenance->id }}" class="form-label fw-bold small text-uppercase">Total Biaya (Rp)</label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                                                                <input type="number" class="form-control border-start-0" id="cost-{{ $maintenance->id }}" name="cost" value="0" min="0">
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="py-5 text-center text-muted">
                                                <i class="bi bi-calendar-check fs-1 opacity-25"></i>
                                                <p class="mt-2 mb-0">Tidak ada jadwal maintenance mendatang.</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History -->
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history fs-4 text-secondary"></i>
                            <h5 class="mb-0 fw-bold">Riwayat Maintenance (Selesai)</h5>
                        </div>
                        
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <!-- Date Filter Form -->
                            <form action="{{ route('maintenances.index') }}" method="GET" class="d-flex align-items-center gap-2 me-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0 text-muted">Dari</span>
                                    <input type="date" name="start_date" class="form-control border-start-0" value="{{ $startDate }}">
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0 text-muted">S/D</span>
                                    <input type="date" name="end_date" class="form-control border-start-0" value="{{ $endDate }}">
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary px-3 rounded-2" title="Filter Tanggal">
                                    <i class="bi bi-filter"></i>
                                </button>
                                @if($startDate || $endDate)
                                    <a href="{{ route('maintenances.index') }}" class="btn btn-sm btn-outline-secondary rounded-2" title="Reset Filter">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                            </form>

                            <!-- Export Button -->
                            <button type="button" class="btn btn-sm btn-outline-success px-3 fw-bold rounded-2" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="bi bi-printer me-1"></i> Cetak Laporan
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-uppercase small fw-bold text-muted">
                                    <tr>
                                        <th class="ps-4 py-3">Aset</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi Perbaikan</th>
                                        <th class="ps-3">Biaya</th>
                                        <th>Penanggung Jawab</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($completedMaintenances->count() > 0)
                                        @foreach($completedMaintenances as $maintenance)
                                            <tr>
                                                <td class="ps-4">
                                                    <a href="{{ route('inventaris.show', $maintenance->asset_id) }}" class="text-decoration-none text-dark fw-bold d-block">
                                                        {{ $maintenance->asset->display_name }}
                                                    </a>
                                                    <small class="text-muted">{{ ucfirst($maintenance->asset->category) }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">{{ $maintenance->maintenance_date->format('d M Y') }}</div>
                                                </td>
                                                <td>
                                                    @if($maintenance->title)
                                                        <div class="small fw-bold text-primary">{{ $maintenance->title }}</div>
                                                    @endif
                                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($maintenance->description, 60) }}</div>
                                                </td>
                                                <td class="ps-3">
                                                    <div class="fw-bold text-dark">Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</div>
                                                </td>
                                                <td>
                                                    <div class="small d-flex align-items-center gap-1">
                                                        <i class="bi bi-person-circle text-muted"></i>
                                                        {{ $maintenance->performedByUser->name ?? 'System' }}
                                                    </div>
                                                </td>
                                                <td class="text-center pe-4">
                                                    <form action="{{ route('maintenances.destroy', $maintenance->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 confirm-delete" title="Hapus Log">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="py-5 text-center text-muted">
                                                <i class="bi bi-inbox fs-2 opacity-25"></i>
                                                <p class="mb-0 mt-2">Belum ada riwayat maintenance yang ditemukan.</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 border-top">
                            {{ $completedMaintenances->links() }}
                        </div>
                    </div>
                </div>

                <!-- Export Modal -->
                <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">Pilih Format Laporan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <p class="text-muted mb-4 small">Laporan akan mencakup riwayat maintenance berdasarkan filter tanggal yang sedang aktif.</p>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <a href="{{ route('maintenances.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                            class="btn btn-outline-danger w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-3">
                                            <i class="bi bi-file-earmark-pdf fs-1"></i>
                                            <span class="fw-bold">Format PDF</span>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('maintenances.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                            class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-3">
                                            <i class="bi bi-file-earmark-excel fs-1"></i>
                                            <span class="fw-bold">Format EXCEL</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
