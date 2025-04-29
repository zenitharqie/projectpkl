<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Customer;
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
        \DB::beginTransaction();

        try {
            \Log::info('Inquiry submission started', $request->all());

            // Validasi data
            $request->validate([
                'customer_name' => 'required_without:customer_id|string|max:255',
                'customer_phone' => 'required_without:customer_id|string|max:20',
                'customer_email' => [
                    'required_without:customer_id',
                    'email',
                    'max:255',
                    function ($attr, $value, $fail) use ($request) {
                        if (empty($request->customer_id) && Customer::where('email', $value)->exists()) {
                            $fail('Email already exists for another customer.');
                        }
                    }
                ],
                'customer_id' => 'nullable|exists:customers,id',
                'business_unit' => 'required|string',
                'sales_name' => 'nullable|string|max:255',
                'end_user' => 'required|string|max:255',
                'pic_engineering' => 'required|string|max:255',
                'email_engineering' => 'required|email|max:255',
                'description' => 'required|string',
                'inquiry_date' => 'required|date|after_or_equal:today',
                'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);

            // Handle customer
            $customerId = $request->customer_id;

            if (empty($customerId)) {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'email' => $request->customer_email
                ]);
                $customerId = $customer->id;
                \Log::info('New customer created', ['id' => $customerId]);
            }

            // Handle file upload
            $documentPath = $request->hasFile('document')
                ? $request->file('document')->store('inquiry_documents', 'public')
                : null;

            // Create inquiry
            $inquiry = Inquiry::create([
                'customer_id' => $customerId,
                'business_unit' => $request->business_unit,
                'sales_name' => $request->sales_name,
                'end_user' => $request->end_user,
                'pic_engineering' => $request->pic_engineering,
                'email_engineering' => $request->email_engineering,
                'description' => $request->description,
                'inquiry_date' => $request->inquiry_date,
                'status' => 'pending',
                'document' => $documentPath,
            ]);

            // Removed automatic quotation creation on inquiry document upload to prevent duplicate records
            // if ($documentPath) {
            //     \Log::info('Creating quotation record for inquiry document upload', ['documentPath' => $documentPath, 'inquiry_id' => $inquiry->id]);
            //     try {
            //         $quotation = \App\Models\Quotation::create([
            //             'inquiry_id' => $inquiry->id,
            //             'customer_id' => $customerId,
            //             'inquiry_date' => $request->inquiry_date,
            //             'due_date' => null,
            //             'quotation_file' => $documentPath,
            //             'status_quotation' => 'pending',
            //             'email_customer' => $request->customer_email ?? '',
            //             'sales' => $request->sales_name ?? '',
            //             'quotation_number' => 'AUTO-' . $inquiry->id . '-' . time(),
            //             'email_sent_at' => null,
            //         ]);
            //         \Log::info('Quotation record created successfully', ['quotation_id' => $quotation->id]);
            //     } catch (\Exception $e) {
            //         \Log::error('Failed to create quotation record from inquiry document upload: ' . $e->getMessage());
            //     }
            // }

            \DB::commit();
            \Log::info('Inquiry created successfully', ['id' => $inquiry->id]);

            return redirect()
                ->route('inquiries.index')
                ->with('success', 'Inquiry submitted successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            \Log::warning('Validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Inquiry submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to submit inquiry. Please try again.');
        }
    }

    // Method untuk menampilkan list inquiry di admin dashboard
    public function listInquiries(Request $request)
    {
        // Ambil parameter dari request
        $status = $request->query('status', 'all');
        $year = $request->query('year');
        $month = $request->query('month');
        $day = $request->query('day');
        $id = $request->query('id');
        $customerName = $request->query('customer_name');

        // Query with eager loading including soft-deleted customers
        $query = Inquiry::with(['customer' => function($query) {
            $query->withTrashed();
        }, 'quotations'])->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter berdasarkan tahun
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        // Filter berdasarkan bulan
        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        // Filter berdasarkan hari
        if ($day) {
            $query->whereDay('created_at', $day);
        }

        // Filter berdasarkan ID
        if ($id) {
            $query->where('id', $id);
        }

        // Filter berdasarkan nama pelanggan
        if ($customerName) {
            $query->whereHas('customer', function($q) use ($customerName) {
                $q->where('name', 'like', '%' . $customerName . '%');
            });
        }

        // Ambil data inquiries
        $inquiries = $query->get();

        // Tampilkan view admin/listinquiry dengan data inquiries dan filter
        return view('admin.listinquiry', compact('inquiries', 'status', 'year', 'month', 'day', 'id', 'customerName'));
    }

    // Method untuk memperbarui status inquiry
    public function updateStatus(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'status' => 'required|in:pending,process,completed', // Validasi status
        ]);

        // Temukan inquiry berdasarkan ID
        $inquiry = Inquiry::findOrFail($id);

        // Update status inquiry
        $inquiry->status = $request->status;
        $inquiry->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.listinquiry')->with('success', 'Status updated successfully!');
    }

    // Method untuk menghapus inquiry
    public function destroy($id)
    {
        $inquiry = Inquiry::find($id); // Cari inquiry berdasarkan ID
        if ($inquiry) {
            $inquiry->delete(); // Hapus inquiry dari database
            return response()->json(['success' => true, 'message' => 'Inquiry deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Inquiry not found.'], 404);
    }

    public function searchCustomers(Request $request)
    {
        $term = $request->get('term');

        \Log::info('Searching customers for term: '.$term);

        $customers = Customer::where(function($query) use ($term) {
                $query->where('name', 'LIKE', '%'.$term.'%')
                      ->orWhere('email', 'LIKE', '%'.$term.'%')
                      ->orWhere('phone', 'LIKE', '%'.$term.'%');
            })
            ->get(['id', 'name', 'email', 'phone'])
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone
                ];
            });

        \Log::debug('Found customers:', $customers->toArray());

        return response()->json($customers);
    }

    // New method to show edit form
    public function edit($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('user.inquiryform', compact('inquiry'));
    }

    // New method to update inquiry
    public function update(Request $request, $id)
    {
        \DB::beginTransaction();

        try {
            $inquiry = Inquiry::findOrFail($id);

            // Validate request
            $request->validate([
                'customer_name' => 'required_without:customer_id|string|max:255',
                'customer_phone' => 'required_without:customer_id|string|max:20',
                'customer_email' => [
                    'required_without:customer_id',
                    'email',
                    'max:255',
                    function ($attr, $value, $fail) use ($request, $id) {
                        if (empty($request->customer_id) && Customer::where('email', $value)->where('id', '!=', $id)->exists()) {
                            $fail('Email already exists for another customer.');
                        }
                    }
                ],
                'customer_id' => 'nullable|exists:customers,id',
                'business_unit' => 'required|string',
                'sales_name' => 'nullable|string|max:255',
                'end_user' => 'required|string|max:255',
                'pic_engineering' => 'required|string|max:255',
                'email_engineering' => 'required|email|max:255',
                'description' => 'required|string',
                'inquiry_date' => 'required|date|after_or_equal:today',
                'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);

            // Handle customer
            $customerId = $request->customer_id;

            if (empty($customerId)) {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'email' => $request->customer_email
                ]);
                $customerId = $customer->id;
            } else {
                $customer = Customer::find($customerId);
                if ($customer) {
                    $customer->update([
                        'name' => $request->customer_name,
                        'phone' => $request->customer_phone,
                        'email' => $request->customer_email
                    ]);
                }
            }

            // Handle file upload
            $documentPath = $inquiry->document;
            if ($request->hasFile('document')) {
                // Delete old document if exists
                if ($documentPath) {
                    Storage::disk('public')->delete($documentPath);
                }
                $documentPath = $request->file('document')->store('inquiry_documents', 'public');
            }

            // Update inquiry
            $inquiry->update([
                'customer_id' => $customerId,
                'business_unit' => $request->business_unit,
                'sales_name' => $request->sales_name,
                'end_user' => $request->end_user,
                'pic_engineering' => $request->pic_engineering,
                'email_engineering' => $request->email_engineering,
                'description' => $request->description,
                'inquiry_date' => $request->inquiry_date,
                'document' => $documentPath,
            ]);

            \DB::commit();

            return redirect()
                ->route('admin.listinquiry')
                ->with('success', 'Inquiry updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update inquiry. Please try again.');
        }
    }
}
