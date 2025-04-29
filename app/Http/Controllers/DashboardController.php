<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Method untuk menampilkan dashboard admin
    public function index()
    {
        // Ambil total inquiries
        $totalInquiries = Inquiry::count();
    
        // Removed inquiries and quotations status counts as per user request
        // So these variables are not passed to view anymore
        // $pendingInquiries = Inquiry::where('status', 'pending')->count();
        // $processInquiries = Inquiry::where('status', 'process')->count();
        // $completedInquiries = Inquiry::where('status', 'completed')->count();

        $totalQuotations = Quotation::count();

        // $processQuotations = Quotation::where('status_quotation', 'process')->count();
        // $completedQuotations = Quotation::where('status_quotation', 'completed')->count();

        // Placeholder for POs count (logic empty)
        $totalPOs = 0;

        // Placeholder for Expired Quotations count (logic empty)
        $expiredQuotations = 0;
    
        // Ambil jumlah inquiries berdasarkan waktu
        $todayInquiries = Inquiry::whereDate('created_at', today())->count();
        $thisMonthInquiries = Inquiry::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $thisYearInquiries = Inquiry::whereYear('created_at', date('Y'))->count();
    
        // Ambil 2 inquiry terbaru
        $recentInquiries = Inquiry::orderBy('created_at', 'desc')->take(2)->get();
    
        // Ambil data untuk grafik harian
        $dailyInquiries = Inquiry::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->take(30) // Ambil 30 hari terakhir
            ->get();
    
        // Ambil data untuk grafik bulanan
        $monthlyInquiries = Inquiry::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->take(12) // Ambil 12 bulan terakhir
            ->get();
    
        // Ambil data untuk grafik tahunan
        $yearlyInquiries = Inquiry::select(DB::raw('YEAR(created_at) as year'), DB::raw('count(*) as count'))
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->take(5) // Ambil 5 tahun terakhir
            ->get();

        // Ambil distinct business units dari inquiries yang sudah menjadi quotations
        $businessUnits = Inquiry::whereIn('id', function ($query) {
                $query->select('inquiry_id')->from('quotations');
            })
            ->select('business_unit')
            ->distinct()
            ->pluck('business_unit');

        // Kirim data ke view
        return view('admin.dashboard', compact(
            'totalInquiries',
            'totalQuotations',
            'totalPOs',
            'expiredQuotations',
            'todayInquiries',
            'thisMonthInquiries',
            'thisYearInquiries',
            'recentInquiries',
            'dailyInquiries',
            'monthlyInquiries',
            'yearlyInquiries',
            'businessUnits'
        ));
    }
}
