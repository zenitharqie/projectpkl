<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use Illuminate\Support\Facades\Storage;
use App\Mail\QuotationMail; 
use Illuminate\Support\Facades\Mail; 
use App\Models\Inquiry;
use Carbon\Carbon;

class QuotationController extends Controller
{
    public function create()
    {
        $inquiries = Inquiry::where('status', 'pending')
            ->with('customer')
            ->get();
            
        return view('admin.quotationform', compact('inquiries'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'inquiry_id' => 'required|exists:inquiries,id',
            'attachment' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $inquiry = Inquiry::with('customer')->findOrFail($request->input('inquiry_id'));

        // Store uploaded file with better naming convention
        $file = $request->file('attachment');
        $fileName = 'QUOT-' . $inquiry->id . '-' . time() . '.' . $file->getClientOriginalExtension();

        try {
            $filePath = $file->storeAs('quotations', $fileName);
        } catch (\Exception $e) {
            \Log::error('Failed to store quotation file: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload file. Please try again.');
        }

        // Create quotation record with all relevant attributes
        try {
            $quotation = Quotation::create([
                'inquiry_id' => $inquiry->id,
                'customer_id' => $inquiry->customer_id,
                'inquiry_date' => $inquiry->inquiry_date ?? now()->toDateString(),
                'due_date' => null,
                'quotation_file' => $filePath,
                'status_quotation' => 'pending',
                'email_customer' => $inquiry->customer->email ?? '',
                'sales' => $inquiry->sales_name ?? '',
                'quotation_number' => $this->generateQuotationNumber(),
                'email_sent_at' => null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create quotation record: ' . $e->getMessage());
            // Delete uploaded file if record creation fails
            if (isset($filePath) && \Illuminate\Support\Facades\Storage::exists($filePath)) {
                \Illuminate\Support\Facades\Storage::delete($filePath);
            }
            return back()->with('error', 'Failed to create quotation record. Please try again.');
        }

        // Remove updating inquiry status to keep statuses independent
        // try {
        //     $inquiry->update(['status' => 'process']);
        // } catch (\Exception $e) {
        //     \Log::error('Failed to update inquiry status: ' . $e->getMessage());
        // }

        // Send email with error handling
        try {
            Mail::to($quotation->email_customer)->send(new QuotationMail($quotation));
            $quotation->update(['email_sent_at' => now()]);
        } catch (\Exception $e) {
            \Log::error('Failed to send quotation email: ' . $e->getMessage());
        }

        return redirect()->route('admin.listquotation')->with('success', 'Quotation created successfully.');
    }

    public function show($id)
    {
        $quotation = Quotation::with(['inquiry', 'customer', 'items'])->findOrFail($id);
        return view('admin.quotationdetail', compact('quotation'));
    }

    public function index()
    {
        $quotations = Quotation::with(['customer', 'inquiry', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.listquotation', compact('quotations'));
    }
    
    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        
        // Delete associated file
        if ($quotation->quotation_file && Storage::exists($quotation->quotation_file)) {
            Storage::delete($quotation->quotation_file);
        }

        $quotation->delete();
        
        return redirect()->route('admin.listquotation')->with('success', 'Quotation deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,process,completed',
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->update(['status_quotation' => $request->status]);

        return back()->with('success', 'Quotation status updated.');
    }

    public function updateDueDate(Request $request, $id)
    {
        $request->validate([
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->update(['due_date' => $request->due_date]);

        return back()->with('success', 'Due date updated.');
    }

    public function download($id)
    {
        $quotation = Quotation::findOrFail($id);
        
        if (!Storage::exists($quotation->quotation_file)) {
            abort(404);
        }

        return Storage::download($quotation->quotation_file);
    }

    public function resendEmail($id)
    {
        $quotation = Quotation::findOrFail($id);

        try {
            Mail::to($quotation->email_customer)->send(new QuotationMail($quotation));
            $quotation->update(['email_sent_at' => now()]);
            return back()->with('success', 'Quotation email resent successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to resend quotation email: ' . $e->getMessage());
            return back()->with('error', 'Failed to resend email.');
        }
    }

    protected function generateQuotationNumber()
    {
        $prefix = 'QUOT';
        $year = date('Y');
        $month = date('m');
        $lastQuotation = Quotation::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastQuotation ? (int) substr($lastQuotation->quotation_number, -4) + 1 : 1;
        
        return sprintf("%s-%s%s-%04d", $prefix, $year, $month, $sequence);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'due_date' => 'nullable|date|after_or_equal:today',
            'email_customer' => 'required|email|max:255',
        ]);

        $quotation = Quotation::findOrFail($id);

        // Update customer name in related customer record if needed
        if ($quotation->customer) {
            $quotation->customer->update(['name' => $validatedData['customer_name']]);
        }

        // Update quotation fields
        $quotation->update([
            'due_date' => $validatedData['due_date'],
            'email_customer' => $validatedData['email_customer'],
        ]);

        return redirect()->route('admin.listquotation')->with('success', 'Quotation updated successfully.');
    }
}