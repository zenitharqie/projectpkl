<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Storage;

class InquiryController extends Controller
{
    // Method untuk menampilkan form inquiry
    public function index()
    {
        return view('user.inquiryform');
    }

    // Method untuk menyimpan data inquiry
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'required|date',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Validasi untuk file
        ]);

        // Handle file upload
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
        }

        // Simpan data ke database
        Inquiry::create([
            'customer_name' => $request->customer_name,
            'item_code' => $request->item_code,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'due_date' => $request->due_date,
            'status' => false, // Default status: pending
            'document' => $documentPath, // Simpan path dokumen
        ]);

        // Redirect ke halaman form dengan pesan sukses
        return redirect()->route('inquiries.index')->with('success', 'Inquiry submitted successfully!');
    }

    // Method untuk menampilkan list inquiry di admin dashboard
    public function listInquiries()
    {
        // Ambil semua data inquiry dari database
        $inquiries = Inquiry::orderBy('id', 'desc')->get();

        // Tampilkan view admin/listinquiry dengan data inquiries
        return view('admin.listinquiry', compact('inquiries'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'status' => 'required|in:pending,process,completed', // Tambahkan opsi 'process'
        ]);

        // Temukan inquiry berdasarkan ID
        $inquiry = Inquiry::findOrFail($id);

        // Update status inquiry
        $inquiry->status = $request->status;
        $inquiry->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.listinquiry')->with('success', 'Status updated successfully!');
    }

    public function destroy($id)
{
    $inquiry = Inquiry::find($id); // Cari inquiry berdasarkan ID
    if ($inquiry) {
        $inquiry->delete(); // Hapus inquiry dari database
        return redirect()->route('admin.inquirylist')->with('success', 'Inquiry deleted successfully.');
    }
    return redirect()->route('admin.inquirylist')->with('error', 'Inquiry not found.');
}
}