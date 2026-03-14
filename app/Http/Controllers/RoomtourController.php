<?php

namespace App\Http\Controllers;

use App\Models\Roomtour;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomtourController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));

        $query = Roomtour::whereDate('schedule', '>=', $startDate)
            ->whereDate('schedule', '<=', $endDate);

        $estimasiOmset = (clone $query)->sum('nominal');
        $totalOrder = (clone $query)->count();
        $pendingOrder = (clone $query)->where('status', 'pending')->count();

        $transactions = (clone $query)->orderBy('schedule', 'asc')
            ->paginate(10)
            ->withQueryString();

        $showSlotKosong = ($startDate == $endDate);
        $bookedSlots = [];
        $allSlots = [];

        if ($showSlotKosong) {
            for ($i = 0; $i < 24; $i++) {
                $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                $allSlots[] = "$hour:00";
                $allSlots[] = "$hour:30";
            }

            $bookingsOnDate = Roomtour::whereDate('schedule', $startDate)->get();

            foreach ($bookingsOnDate as $booking) {
                $start = Carbon::parse($booking->schedule);
                $durationHours = (float) filter_var($booking->duration, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                if ($durationHours <= 0) $durationHours = 1;

                $end = (clone $start)->addMinutes($durationHours * 60);

                foreach ($allSlots as $slot) {
                    $slotTime = Carbon::parse($startDate . ' ' . $slot);
                    if ($slotTime >= $start && $slotTime < $end) {
                        $bookedSlots[] = $slot;
                    }
                }
            }

            if ($startDate == Carbon::today()->format('Y-m-d')) {
                $currentTime = Carbon::now()->timezone('Asia/Jakarta');
                foreach ($allSlots as $slot) {
                    $slotTime = Carbon::parse($startDate . ' ' . $slot);
                    if ($slotTime < $currentTime) {
                        if (!in_array($slot, $bookedSlots)) {
                            $bookedSlots[] = $slot;
                        }
                    }
                }
            }
        }

        return view('admin.dashboard', compact(
            'estimasiOmset',
            'totalOrder',
            'pendingOrder',
            'transactions',
            'allSlots',
            'bookedSlots',
            'startDate',
            'endDate',
            'showSlotKosong'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_wa' => 'required',
            'duration'    => 'required',
            'schedule'    => 'required',
            'nominal'     => 'required|numeric',
        ]);

        $newStart = \Carbon\Carbon::parse($request->schedule);
        $durationHours = (float) filter_var($request->duration, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if ($durationHours <= 0) $durationHours = 1;
        $newEnd = (clone $newStart)->addMinutes($durationHours * 60);

        $existingBookings = \App\Models\Roomtour::whereDate('schedule', $newStart->format('Y-m-d'))->get();

        foreach ($existingBookings as $booking) {
            $existingStart = \Carbon\Carbon::parse($booking->schedule);
            $existingDuration = (float) filter_var($booking->duration, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $existingEnd = (clone $existingStart)->addMinutes($existingDuration * 60);

            if ($newStart < $existingEnd && $newEnd > $existingStart) {
                return back()->with('error', 'Waduh Div! Jam tersebut sudah di-booked oleh ' . $booking->customer_wa . ' (' . $existingStart->format('H:i') . ' - ' . $existingEnd->format('H:i') . ')');
            }
        }

        $isSplit = $request->note && str_contains(strtolower($request->note), 'bagi 2');

        \App\Models\Roomtour::create([
            'customer_wa' => $request->customer_wa,
            'duration'    => $request->duration,
            'schedule'    => $request->schedule,
            'nominal'     => $request->nominal,
            'gaji_status' => $request->gaji_status ?? 'pending',
            'staff'       => $request->staff ?? 'nadiv',
            'status'      => 'pending',
            'note'        => $request->note,
            'is_split'    => $isSplit,
        ]);

        return redirect()->route('dashboard')->with('success', 'Booking berhasil dicatat!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_wa' => 'required',
            'nominal'     => 'required|numeric',
            'duration'    => 'required',
            'schedule'    => 'required',
        ]);

        $roomtour = Roomtour::findOrFail($id);
        $isSplit = $request->note && str_contains(strtolower($request->note), 'bagi 2');

        $roomtour->update([
            'customer_wa' => $request->customer_wa,
            'schedule'    => $request->schedule,
            'nominal'     => $request->nominal,
            'duration'    => $request->duration,
            'gaji_status' => $request->gaji_status,
            'staff'       => $request->staff,
            'status'      => $request->status,
            'note'        => $request->note,
            'is_split'    => $isSplit,
        ]);

        return back()->with('success', 'Data booking berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Roomtour::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus!');
    }

    public function gridView(Request $request)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $bookings = Roomtour::whereBetween('schedule', [$startOfWeek, $endOfWeek])->get();

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = (clone $startOfWeek)->addDays($i);
        }

        $slots = [];
        for ($i = 0; $i < 24; $i++) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $slots[] = "$hour:00";
            $slots[] = "$hour:30";
        }

        return view('admin.grid', compact('days', 'slots', 'bookings'));
    }
}