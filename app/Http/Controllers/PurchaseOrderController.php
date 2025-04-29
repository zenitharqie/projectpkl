<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    // Show the purchase order form
    public function index()
    {
        return view('Admin.purchaseorderform');
    }

    // Show the purchase order list
    public function list(Request $request)
    {
        $query = PurchaseOrder::with(['quotation.customer', 'quotation.inquiry'])
            ->orderBy('purchaseorder_ID', 'desc');

        if ($request->has('poid') && $request->poid != '') {
            $query->where('purchaseorder_ID', $request->poid);
        }

        $purchaseOrders = $query->get();

        // Check user authorization for viewing purchase order list
        if (!auth()->check()) {
            abort(403, 'Forbidden');
        }

        return view('Admin.purchaseorder', compact('purchaseOrders'));
    }

    // Search for a quotation by ID and return related data
    public function searchQuotation(Request $request)
    {
        $qid = $request->input('QID');

        $quotation = Quotation::with(['inquiry', 'customer'])->find($qid);

        if (!$quotation) {
            return response()->json(['error' => 'Quotation not found'], 404);
        }

        $inquiry = $quotation->inquiry;
        $customer = $quotation->customer;

        return response()->json([
            'Customer_Name' => $customer ? $customer->name : null,
            'Email_Customer' => $customer ? $customer->email : null,
            'End_User' => $inquiry ? $inquiry->end_user : null,
            'Business_Unit' => $inquiry ? $inquiry->business_unit : null,
            'Sales_Name' => $inquiry ? $inquiry->sales_name : null,
        ]);
    }

    // Store the purchase order data
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'QID' => 'required|exists:quotations,id',
            'PO_Date' => 'required|date',
            'Job_Number' => 'required|string|max:255',
            'Contract_Number' => 'required|string|max:255',
            'po_value' => 'required|numeric|min:0',
            'Upload_File' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $uploadFilePath = null;
        if ($request->hasFile('Upload_File')) {
            $uploadFilePath = $request->file('Upload_File')->store('purchaseorders', 'public');
        }

        PurchaseOrder::create([
            'QID' => $request->input('QID'),
            'PO_Date' => $request->input('PO_Date'),
            'Job_Number' => $request->input('Job_Number'),
            'Contract_Number' => $request->input('Contract_Number'),
            'po_value' => $request->input('po_value'),
            'Upload_File' => $uploadFilePath,
        ]);

        return redirect()->route('purchaseorder.index')->with('success', 'Purchase Order created successfully.');
    }

    // Autocomplete search for Quotation IDs
    public function autocompleteQuotation(Request $request)
    {
        $search = $request->input('term');

        $quotations = \App\Models\Quotation::where('id', 'like', '%' . $search . '%')
            ->limit(10)
            ->pluck('id');

        return response()->json($quotations);
    }

    // Delete a purchase order
    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        // Delete the uploaded file if exists
        if ($purchaseOrder->Upload_File) {
            \Illuminate\Support\Facades\Storage::delete($purchaseOrder->Upload_File);
        }
        $purchaseOrder->delete();

        return redirect()->route('purchaseorder.list')->with('success', 'Purchase Order deleted successfully.');
    }

    // Update status of a purchase order
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:N/A,Wait in Progress,Close,Cancel,Account Receivable,Income Before Tax',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->status = $request->input('status');
        $purchaseOrder->save();

        return redirect()->route('purchaseorder.list')->with('success', 'Purchase Order status updated successfully.');
    }
}
