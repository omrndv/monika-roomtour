@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4 md:p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Jadwal Mingguan</h2>
        <div class="text-xs text-slate-500 font-medium bg-slate-100 px-4 py-2 rounded-lg">
            <i class="fas fa-info-circle mr-1"></i> Klik Mode List di Navbar untuk input data baru
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-bold text-slate-400 uppercase sticky left-0 bg-slate-50 z-10 border-r w-20">Jam</th>
                        @foreach($days as $day)
                        <th class="p-4 text-xs font-bold text-slate-600 uppercase min-w-[150px] {{ $day->isToday() ? 'bg-indigo-50 text-indigo-600' : '' }}">
                            {{ $day->translatedFormat('D') }}<br>
                            <span class="text-[10px] text-slate-400 font-normal">{{ $day->format('d M') }}</span>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($slots as $slot)
                    <tr>
                        <td class="p-3 text-[10px] font-bold text-slate-400 text-center sticky left-0 bg-white z-10 border-r shadow-[2px_0_5px_rgba(0,0,0,0.05)]">
                            {{ $slot }}
                        </td>

                        @foreach($days as $day)
                        @php
                        $currentDateTime = \Carbon\Carbon::parse($day->format('Y-m-d') . ' ' . $slot);

                        $booking = $bookings->first(function($item) use ($currentDateTime) {
                        $start = $item->schedule;
                        $duration = (float) filter_var($item->duration, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $end = (clone $start)->addMinutes(($duration ?: 1) * 60);
                        return $currentDateTime >= $start && $currentDateTime < $end;
                            });

                            $bgColor='bg-slate-100' ;
                            $textColor='text-slate-600' ;
                            $borderColor='border-slate-200' ;

                            if($booking) {
                            $colorIndex=crc32($booking->customer_wa) % 6;
                            $colors = [
                            ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                            ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'border' => 'border-purple-200'],
                            ['bg' => 'bg-pink-100', 'text' => 'text-pink-700', 'border' => 'border-pink-200'],
                            ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                            ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                            ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'border' => 'border-rose-200'],
                            ];

                            $selected = $colors[$colorIndex];
                            $bgColor = $selected['bg'];
                            $textColor = $selected['text'];
                            $borderColor = $selected['border'];

                            if($booking->status == 'done') {
                            $bgColor = str_replace('100', '50', $bgColor);
                            }
                            }
                            @endphp

                            <td class="p-1 border-r border-slate-50 h-12">
                                @if($booking)
                                <div class="h-full w-full rounded-lg p-2 text-[9px] leading-tight border {{ $bgColor }} {{ $textColor }} {{ $borderColor }} shadow-sm">
                                    <div class="flex justify-between items-start">
                                        <span class="font-bold truncate">{{ $booking->customer_wa }}</span>
                                        @if($booking->status == 'done')
                                        <i class="fas fa-check-circle text-[8px] opacity-50"></i>
                                        @endif
                                    </div>
                                    <div class="opacity-75 italic mt-1">{{ $booking->duration }}</div>
                                    <div class="font-bold mt-0.5 text-[10px]">{{ number_format($booking->nominal / 1000, 0) }}k</div>
                                </div>
                                @else
                                <div class="h-full w-full opacity-5 hover:bg-slate-200 transition-colors cursor-pointer rounded"></div>
                                @endif
                            </td>
                            @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .overflow-x-auto {
        scrollbar-width: thin;
        scrollbar-color: #e2e8f0 #ffffff;
    }

    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #ffffff;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background-color: #e2e8f0;
        border-radius: 20px;
    }
</style>
@endsection