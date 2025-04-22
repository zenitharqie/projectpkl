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
    
        // Ambil jumlah inquiries berdasarkan status
        $pendingInquiries = Inquiry::where('status', 'pending')->count();
        $processInquiries = Inquiry::where('status', 'process')->count();
        $completedInquiries = Inquiry::where('status', 'completed')->count();

        // Ambil total quotations
        $totalQuotations = Quotation::count();

        // Ambil jumlah quotations berdasarkan status
        $processQuotations = Quotation::where('status_quotation', 'process')->count();
        $completedQuotations = Quotation::where('status_quotation', 'completed')->count();
    
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
    
        // Kirim data ke view
        return view('admin.dashboard', compact(
            'totalInquiries',
            'pendingInquiries',
            'processInquiries',
            'completedInquiries',
            'totalQuotations',
            'processQuotations',
            'completedQuotations',
            'todayInquiries',
            'thisMonthInquiries',
            'thisYearInquiries',
            'recentInquiries',
            'dailyInquiries',
            'monthlyInquiries',
            'yearlyInquiries'
        ));
    }
}
