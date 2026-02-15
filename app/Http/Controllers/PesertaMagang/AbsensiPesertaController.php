<?php

namespace App\Http\Controllers\PesertaMagang;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\PesertaMagang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiPesertaController extends Controller
{
    // Koordinat Diskominfo SP Surakarta
    const OFFICE_LAT = -7.565962;
    const OFFICE_LNG = 110.826141;
    
    public function index() 
    {
        $user = auth()->user();

        $peserta = null;
        if ($user) {
            $peserta = PesertaMagang::where('id_user', $user->id_user)
                ->orderByDesc('created_at')
                ->first();
        }

        if (!$peserta) {
            return view('peserta.absensi', [
                'infoMessage' => 'Silahkan mengajukan magang terlebih dahulu.',
                'total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0
            ]);
        }

        if ($peserta->status_verifikasi !== 'terverifikasi') {
            return view('peserta.absensi', [
                'infoMessage' => 'Pengajuan sudah masuk. Silahkan tunggu verifikasi admin.',
                'total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0
            ]);
        }

        $pesertaId = $peserta->id_pesertamagang;

        $total = Absensi::where('id_pesertamagang', $pesertaId)->count();

        $hadir = Absensi::where('id_pesertamagang', $pesertaId)
            ->where('status', 'hadir')
            ->count();

        $izin = Absensi::where('id_pesertamagang', $pesertaId)
            ->where('status', 'izin')
            ->count();

        $sakit = Absensi::where('id_pesertamagang', $pesertaId)
            ->where('status', 'sakit')
            ->count();

        $alpha = max(0, $total - ($hadir + $izin + $sakit));

        // Cek status periode magang dan absensi hari ini (timezone Jakarta)
        $todayJakarta = Carbon::now('Asia/Jakarta');
        $todayDate = $todayJakarta->toDateString();
        $endDate = $peserta->tanggal_selesai
            ? Carbon::parse($peserta->tanggal_selesai, 'Asia/Jakarta')->endOfDay()
            : null;
        
        $infoMessage = null;
        $finishedMagang = false;
        $notStarted = false;

        // Jika masa magang sudah lewat, blokir form dan beri pesan perhatian
        if ($endDate && $todayJakarta->gt($endDate)) {
            $infoMessage = 'Masa magang Anda telah selesai. Fitur logbook dan absensi sudah tidak tersedia. Silakan cek sertifikat dan penilaian di halaman yang sesuai. Jika ingin mengikuti program magang kembali, silakan mengajukan pendaftaran ulang.';
            $finishedMagang = true;
        } else {
            $startDate = $peserta->tanggal_mulai
                ? Carbon::parse($peserta->tanggal_mulai, 'Asia/Jakarta')->startOfDay()
                : null;

            if ($startDate && $todayJakarta->lt($startDate)) {
                $notStarted = true;
            } else {
                // Cek apakah sudah absen hari ini
                $alreadyAbsen = Absensi::where('id_pesertamagang', $pesertaId)
                    ->whereDate('waktu_absen', $todayDate)
                    ->exists();

                if ($alreadyAbsen) {
                    $absensiToday = Absensi::where('id_pesertamagang', $pesertaId)
                        ->whereDate('waktu_absen', $todayDate)
                        ->first();
                    
                    $infoMessage = 'Anda sudah absen hari ini pada pukul ' . 
                        Carbon::parse($absensiToday->waktu_absen)->format('H:i') . ' WIB';
                }
            }
        }

        return view('peserta.absensi', compact(
            'total',
            'hadir',
            'izin',
            'sakit',
            'alpha',
            'infoMessage',
            'finishedMagang',
            'notStarted'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit',
            'bukti_kegiatan' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'alasan' => 'required_if:status,izin,sakit|string|min:10',
            'lokasi' => 'nullable|string', // JSON string dengan lat, lng
        ]);

        $user = auth()->user();
        $peserta = null;
        if ($user) {
            $peserta = PesertaMagang::where('id_user', $user->id_user)
                ->orderByDesc('created_at')
                ->first();
        }

        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan.'], 404);
        }

        if ($peserta->status_verifikasi !== 'terverifikasi') {
            return response()->json([
                'message' => 'Pengajuan belum diverifikasi. Anda belum bisa absen.'
            ], 403);
        }

        if (!$peserta->tanggal_mulai) {
            return response()->json([
                'message' => 'Tanggal mulai magang belum ditetapkan.'
            ], 422);
        }

        // Gunakan timezone Jakarta
        $todayJakarta = Carbon::now('Asia/Jakarta');
        $start = Carbon::parse($peserta->tanggal_mulai, 'Asia/Jakarta');
        // Gunakan endOfDay supaya tanggal_selesai masih bisa dipakai absen sepanjang hari terakhir
        $end = $peserta->tanggal_selesai ? 
            Carbon::parse($peserta->tanggal_selesai, 'Asia/Jakarta')->endOfDay() : null;

        if ($todayJakarta->lt($start)) {
            return response()->json([
                'message' => 'Belum waktunya absen. Absensi dimulai tanggal ' . 
                    $start->format('d M Y')
            ], 422);
        }

        if ($end && $todayJakarta->gt($end)) {
            return response()->json([
                'message' => 'Masa magang sudah selesai. Anda tidak bisa absen lagi.'
            ], 422);
        }

        // Cek apakah sudah absen hari ini
        $alreadyAbsen = Absensi::where('id_pesertamagang', $peserta->id_pesertamagang)
            ->whereDate('waktu_absen', $todayJakarta->toDateString())
            ->exists();

        if ($alreadyAbsen) {
            return response()->json(['message' => 'Anda sudah absen hari ini'], 409);
        }

        // Upload file
        $path = $request->file('bukti_kegiatan')
            ->store('bukti-absensi', 'public');

        // Parse lokasi dan hitung jarak
        $jarakMeter = null;
        $lokasiText = null;
        
        if ($request->lokasi && $request->status === 'hadir') {
            $lokasiData = json_decode($request->lokasi, true);
            if ($lokasiData && isset($lokasiData['lat']) && isset($lokasiData['lng'])) {
                // Hitung jarak dari koordinat Diskominfo
                $jarakMeter = $this->calculateDistance(
                    $lokasiData['lat'],
                    $lokasiData['lng'],
                    self::OFFICE_LAT,
                    self::OFFICE_LNG
                );
                
                $lokasiText = "Jarak: " . round($jarakMeter) . " meter dari Diskominfo";
            }
        }

        // Simpan dengan waktu realtime Jakarta
        Absensi::create([
            'id_pesertamagang' => $peserta->id_pesertamagang,
            'waktu_absen'      => $todayJakarta, // Waktu realtime WIB
            'status'           => $request->status,
            'lokasi'           => $lokasiText, // Hanya jarak dalam meter
            'bukti_kegiatan'   => $path,
            'alasan'           => $request->alasan,
        ]);

        return response()->json([
            'message' => 'Absen berhasil pada ' . $todayJakarta->format('H:i:s') . ' WIB'
        ]);
    }

    /**
     * Calculate distance between two coordinates in meters
     * menggunakan Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth radius in meters
        
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);
        
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        
        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * 
            pow(sin($lonDelta / 2), 2)
        ));
        
        return $angle * $earthRadius;
    }
}
