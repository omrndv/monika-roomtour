@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4 md:p-8">

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-slate-500 text-xs font-bold uppercase mb-1">Estimasi Omset</p>
            <h2 class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($estimasiOmset, 0, ',', '.') }}</h2>
            <p class="text-slate-400 text-[10px] mt-1 italic">*Semua nominal masuk</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-slate-500 text-xs font-bold uppercase mb-1">Total Order</p>
            <h2 class="text-2xl font-extrabold text-slate-800">{{ $totalOrder }}</h2>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-slate-500 text-xs font-bold uppercase mb-1">Belum Diproses</p>
            <h2 class="text-2xl font-extrabold text-orange-600">{{ $pendingOrder }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2">
            <form action="{{ route('roomtour.store') }}" method="POST" class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100 h-full shadow-sm">
                @csrf
                <h3 class="font-bold text-indigo-900 mb-4 text-sm uppercase flex items-center">
                    <i class="fas fa-plus-circle mr-2 text-indigo-500"></i> Input Booking Baru
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <input type="text" name="customer_wa" placeholder="Nomor WA Customer" class="w-full p-3 bg-white border-0 rounded-xl text-sm outline-none shadow-sm focus:ring-2 focus:ring-indigo-300 transition" required>
                    <input type="datetime-local" name="schedule" class="w-full p-3 bg-white border-0 rounded-xl text-sm outline-none shadow-sm focus:ring-2 focus:ring-indigo-300 transition" required>

                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="nominal" placeholder="Nominal" class="p-3 bg-white border-0 rounded-xl text-sm outline-none shadow-sm focus:ring-2 focus:ring-indigo-300 transition" required>
                        <input type="text" name="duration" placeholder="Durasi (Jam)" class="p-3 bg-white border-0 rounded-xl text-sm outline-none shadow-sm focus:ring-2 focus:ring-indigo-300 transition" required>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <select name="staff" class="p-3 bg-white border-0 rounded-xl text-sm text-slate-500 outline-none shadow-sm focus:ring-2 focus:ring-indigo-300 transition">
                            <option value="nadiv">Staff: Nadiv</option>
                            <option value="yunan">Staff: Yunan</option>
                            <option value="split">Staff: Split</option>
                        </select>
                        <select name="gaji_status" class="p-3 bg-white border-0 rounded-xl text-sm text-slate-500 outline-none shadow-sm focus:ring-2 focus:ring-indigo-300 transition">
                            <option value="pending">Gaji: Belum</option>
                            <option value="transfered">Gaji: Sudah Transfer</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <input type="text" name="note" placeholder="Catatan/Note (Ketik 'bagi 2' untuk split otomatis)" class="w-full p-3 bg-white border-0 rounded-xl text-sm outline-none shadow-sm focus:ring-2 focus:ring-indigo-300 transition">
                    </div>
                </div>
                <button type="submit" class="w-full mt-4 bg-indigo-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-indigo-700 transition transform hover:-translate-y-1">
                    Simpan Booking
                </button>
            </form>
        </div>

        <div class="lg:col-span-1">
            @if($showSlotKosong)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 h-full">
                <h3 class="font-bold text-slate-800 mb-2 flex items-center text-sm">
                    <i class="fas fa-calendar-check mr-2 text-indigo-500"></i> SLOT KOSONG
                </h3>
                <p class="text-[10px] text-slate-500 mb-4 italic">
                    Jadwal: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}
                </p>
                <div class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                    @foreach($allSlots as $slot)
                    <div class="p-2 {{ in_array($slot, $bookedSlots) ? 'bg-slate-50 text-slate-300 line-through border-slate-100' : 'bg-green-50 text-green-600 border-green-200 font-bold' }} rounded-lg text-center text-[10px] border">
                        {{ $slot }}
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-indigo-950 p-6 rounded-2xl shadow-sm flex flex-col items-center justify-center text-center h-full text-white">
                <i class="fas fa-calendar-alt mb-3 text-indigo-400 text-2xl"></i>
                <p class="text-xs font-bold uppercase tracking-wider">Mode Rentang Tanggal</p>
                <p class="text-[10px] opacity-70 mt-2 leading-relaxed">Pilih 1 tanggal untuk cek slot kosong atau buka <b>Jadwal Mingguan</b>.</p>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 md:flex md:justify-between md:items-center space-y-4 md:space-y-0">
            <h3 class="font-bold text-slate-800 uppercase text-xs tracking-wider">Riwayat Roomtour</h3>

            <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <div class="flex items-center bg-white border border-slate-200 rounded-xl px-2 shadow-sm">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="text-xs p-2 outline-none border-0 bg-transparent">
                    <span class="text-slate-400 text-xs mx-1">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="text-xs p-2 outline-none border-0 bg-transparent">
                </div>
                <button type="submit" class="bg-indigo-600 text-white p-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    <i class="fas fa-filter text-xs"></i>
                </button>
                <a href="{{ route('dashboard') }}" class="bg-slate-200 text-slate-600 p-2.5 rounded-xl hover:bg-slate-300 transition shadow-sm">
                    <i class="fas fa-undo text-xs"></i>
                </a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b">
                    <tr>
                        <th class="px-6 py-4 text-center">Jadwal</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Durasi</th>
                        <th class="px-6 py-4 text-center">Nominal</th>
                        <th class="px-6 py-4 text-center">Gaji</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transactions as $t)
                    <tr class="hover:bg-indigo-50/30 transition">
                        <td class="px-6 py-4 font-semibold text-xs text-slate-500 text-center">
                            {{ $t->schedule->format('d M, H.i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-mono text-indigo-600 text-xs">{{ $t->customer_wa }}</div>
                            <div class="text-[9px] text-slate-400 uppercase font-bold mt-1">
                                <i class="fas fa-user-circle mr-1 text-slate-300"></i> {{ $t->staff ?? 'nadiv' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                            <i class="far fa-clock mr-1 text-slate-300 text-[10px]"></i> {{ $t->duration }}
                            @if($t->is_split)
                            <span class="ml-1 text-orange-500 text-[10px]" title="Bagi Hasil Berdua"><i class="fas fa-divide"></i></span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="font-bold text-slate-700 italic">{{ number_format($t->nominal / 1000, 0) }}k</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded text-[9px] font-bold italic {{ $t->gaji_status == 'transfered' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-400' }}">
                                {{ $t->gaji_status == 'transfered' ? 'CAIR' : 'PENDING' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($t->note)
                            <div class="text-[10px] text-slate-500 italic truncate w-32" title="{{ $t->note }}">{{ $t->note }}</div>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded text-[10px] font-bold {{ $t->status == 'done' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ strtoupper($t->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                                <button onclick="openEditModal({{ json_encode($t) }})" class="text-amber-500 hover:text-amber-700 transition p-1"><i class="fas fa-edit"></i></button>
                                <button onclick="confirmDelete({{ $t->id }})" class="text-red-400 hover:text-red-600 transition p-1"><i class="fas fa-trash"></i></button>
                            </div>
                            <form id="delete-form-{{ $t->id }}" action="{{ route('roomtour.destroy', $t->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-slate-50">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-800">Edit Data Booking</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase">WhatsApp Customer</label>
                <input type="text" name="customer_wa" id="edit_wa" class="w-full p-3 mt-1 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Jadwal</label>
                    <input type="datetime-local" name="schedule" id="edit_schedule" class="w-full p-3 mt-1 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Durasi</label>
                    <input type="text" name="duration" id="edit_duration" class="w-full p-3 mt-1 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Nominal</label>
                    <input type="number" name="nominal" id="edit_nominal" class="w-full p-3 mt-1 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Status</label>
                    <select name="status" id="edit_status" class="w-full p-3 mt-1 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none">
                        <option value="pending">PENDING</option>
                        <option value="done">DONE</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Staff</label>
                    <select name="staff" id="edit_staff" class="w-full p-3 mt-1 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none">
                        <option value="nadiv">Nadiv</option>
                        <option value="yunan">Yunan</option>
                        <option value="split">Split</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Status Gaji</label>
                    <select name="gaji_status" id="edit_gaji_status" class="w-full p-3 mt-1 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none">
                        <option value="pending">Belum Transfer</option>
                        <option value="transfered">Sudah Transfer</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase">Note</label>
                <input type="text" name="note" id="edit_note" class="w-full p-3 mt-1 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-indigo-700 transition">Update Data</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openEditModal(data) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editForm').action = "/roomtour/" + data.id;
        document.getElementById('edit_wa').value = data.customer_wa;
        document.getElementById('edit_nominal').value = data.nominal;
        document.getElementById('edit_duration').value = data.duration;
        document.getElementById('edit_status').value = data.status;
        document.getElementById('edit_staff').value = data.staff;
        document.getElementById('edit_note').value = data.note;
        document.getElementById('edit_gaji_status').value = data.gaji_status;

        let date = new Date(data.schedule);
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
        document.getElementById('edit_schedule').value = date.toISOString().slice(0, 16);
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data ini bakal hilang dari rekapan, Nadiv!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus!',
            customClass: {
                popup: 'rounded-3xl'
            }
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
        });
    }
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: "{{ str_contains(session('success'), 'Selamat datang') ? 'Halo, Nadiv! 👋' : 'Mantap, Div!' }}",
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000,
        background: '#ffffff',
        iconColor: '#4f46e5',
        customClass: {
            title: 'font-bold text-slate-800',
            popup: 'rounded-3xl'
        }
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Jadwal Tabrakan! ⚠️',
        text: "{{ session('error') }}",
        confirmButtonColor: '#4f46e5',
        customClass: {
            popup: 'rounded-3xl',
            title: 'font-bold'
        }
    });
</script>
@endif
@endsection