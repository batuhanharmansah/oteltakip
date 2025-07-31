<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\QrScan;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class QrController extends Controller
{
    /**
     * Show QR code management page
     */
    public function index()
    {
        $qrCodes = QrCode::latest()->paginate(10);
        return view('admin.qr-codes.index', compact('qrCodes'));
    }

    /**
     * Show create QR code form
     */
    public function create()
    {
        return view('admin.qr-codes.create');
    }

    /**
     * Store new QR code
     */
    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
        ]);

        $code = Str::random(32);

        $qrCode = QrCode::create([
            'location' => $request->location,
            'code' => $code,
        ]);

        // Generate QR code image
        $this->generateQrImage($qrCode);

        return redirect()->route('admin.qr-codes.index')->with('success', 'QR kod başarıyla oluşturuldu.');
    }

    /**
     * Generate QR code image
     */
    private function generateQrImage(QrCode $qrCode)
    {
        $qr = new EndroidQrCode($qrCode->code);

        // Create directory if it doesn't exist
        $directory = storage_path('app/public/qrcodes');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = 'qr_' . $qrCode->id . '_' . $qrCode->type . '_' . time() . '.png';
        $path = 'qrcodes/' . $filename;

        $writer = new PngWriter();
        $result = $writer->write($qr);

        // Save the QR code image
        $result->saveToFile(storage_path('app/public/' . $path));

        $qrCode->update(['image_path' => $path]);
    }

    /**
     * Download QR code image
     */
    public function download(QrCode $qrCode)
    {
        if (!$qrCode->image_path) {
            $this->generateQrImage($qrCode);
        }

        $path = storage_path('app/public/' . $qrCode->image_path);

        if (!file_exists($path)) {
            $this->generateQrImage($qrCode);
            $path = storage_path('app/public/' . $qrCode->image_path);
        }

        return response()->download($path, 'qr_' . $qrCode->type . '_' . $qrCode->location . '.png');
    }

    /**
     * Delete QR code
     */
    public function destroy(QrCode $qrCode)
    {
        // Delete image file if exists
        if ($qrCode->image_path) {
            $path = storage_path('app/public/' . $qrCode->image_path);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $qrCode->delete();
        return redirect()->route('admin.qr-codes.index')->with('success', 'QR kod başarıyla silindi.');
    }

    /**
     * Process QR code scan
     */
    public function scan(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string',
            ]);

            $qrCode = QrCode::where('code', $request->code)->first();

            if (!$qrCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Geçersiz QR kod.'
                ], 400);
            }

            $user = auth()->user();

            // Check if already scanned today
            $existingScan = QrScan::where('user_id', $user->id)
                ->where('qr_code_id', $qrCode->id)
                ->whereDate('scanned_at', now())
                ->first();

            if ($existingScan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu QR kod bugün zaten okutulmuş.'
                ], 400);
            }

            // Create scan record
            QrScan::create([
                'user_id' => $user->id,
                'qr_code_id' => $qrCode->id,
                'scanned_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR kod taraması başarıyla kaydedildi.',
                'data' => [
                    'location' => $qrCode->location,
                    'scanned_at' => now()->format('H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('QR Scan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'QR kod taraması sırasında bir hata oluştu. Lütfen tekrar deneyin.'
            ], 500);
        }
    }

    /**
     * Show QR scan history
     */
    public function scanHistory(Request $request)
    {
        $query = QrScan::with(['user', 'qrCode']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->whereHas('qrCode', function($q) use ($request) {
                $q->where('location', $request->location);
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('scanned_at', $request->date);
        }

        $qrScans = $query->latest()->paginate(20);

        return view('admin.qr-codes.history', compact('qrScans'));
    }

    /**
     * Export QR scan history to Excel
     */
    public function exportHistory(Request $request)
    {
        $query = QrScan::with(['user', 'qrCode']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->whereHas('qrCode', function($q) use ($request) {
                $q->where('location', $request->location);
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('scanned_at', $request->date);
        }

        $qrScans = $query->latest()->get();

        $filename = 'qr_scan_history_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($qrScans) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Çalışan Adı',
                'Çalışan E-posta',
                'Lokasyon',
                'Tarama Tarihi',
                'Tarama Saati'
            ]);

            foreach ($qrScans as $scan) {
                fputcsv($file, [
                    $scan->user->full_name,
                    $scan->user->email,
                    $scan->qrCode->location,
                    $scan->scanned_at->format('d.m.Y'),
                    $scan->scanned_at->format('H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
