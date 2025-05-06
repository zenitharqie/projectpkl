<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Purchase Order List</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex">
    <!-- Sidebar -->
    <div class="w-64 h-screen bg-white shadow-lg p-5 flex flex-col fixed">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">LNS Indonesia</h2>
        <ul class="space-y-3 flex-1">
            <li><a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md transition duration-200"><i class="fas fa-tachometer-alt mr-3 text-blue-500"></i>Dashboard</a></li>
            <li><a href="{{ url('/admin/listinquiry') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md transition duration-200"><i class="fas fa-list mr-3 text-blue-500"></i>List Inquiry</a></li>
            <li><a href="{{ url('/admin/listquotation') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md transition duration-200"><i class="fas fa-file-alt mr-3 text-blue-500"></i>Quotations</a></li>
            <li><a href="{{ url('/purchaseorder') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md transition duration-200"><i class="fas fa-file-alt mr-3 text-blue-500"></i>Purchase Order Form</a></li>
            <li><a href="{{ url('/admin/purchaseorderlist') }}" class="flex items-center p-3 bg-blue-100 rounded-md transition duration-200 font-medium"><i class="fas fa-clipboard-list mr-3 text-blue-500"></i>Purchase Order List</a></li>
        </ul>
        
    </div>

    <!-- Main Content -->
    <div class="flex-1 ml-64 p-8">
        <div class="max-w-full mx-auto bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Purchase Order List</h1>
            

            <form method="GET" action="{{ route('purchaseorder.list') }}" class="mb-6">
                <label for="poid" class="block text-sm font-medium text-gray-700 mb-1">Filter by PO ID</label>
                <input type="text" id="poid" name="poid" value="{{ request('poid') }}" 
                    class="w-1/4 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-2">
                <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                    Filter
                </button>
                <a href="{{ route('purchaseorder.list') }}" 
                    class="inline-flex items-center px-4 py-2 ml-2 bg-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-200">
                    Clear
                </a>
            </form>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded-md">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left border-b border-gray-300">POID</th>
                            <th class="py-3 px-6 text-left border-b border-gray-300">QID</th>
                            <th class="py-3 px-6 text-left border-b border-gray-300">Contract Number</th>
                            <th class="py-3 px-6 text-left border-b border-gray-300">Customer</th>
                            <th class="py-3 px-6 text-left border-b border-gray-300">Job Number</th>
                            <th class="py-3 px-6 text-left border-b border-gray-300">Contract Date</th>
                            <th class="py-3 px-6 text-left border-b border-gray-300">File PO</th>
                            <th class="py-3 px-6 text-left border-b border-gray-300">Status</th>
                            <th class="py-3 px-6 text-left border-b border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @forelse($purchaseOrders as $po)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $po->purchaseorder_ID }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $po->QID }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $po->Contract_Number }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ optional($po->quotation->customer)->name }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $po->Job_Number }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $po->PO_Date }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                @if($po->Upload_File)
                                    <a href="{{ Storage::url($po->Upload_File) }}" target="_blank" class="text-blue-600 hover:underline">View File</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                <form method="POST" action="{{ route('purchaseorder.updateStatus', $po->purchaseorder_ID) }}">
                                    @csrf
<select name="status" onchange="this.form.submit()" class="rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-sans">
                                        @php
                                            $statuses = ['N/A', 'Wait in Progress', 'Close', 'Cancel', 'Account Receivable', 'Income Before Tax'];
                                        @endphp
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ $po->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                <form method="POST" action="{{ route('purchaseorder.destroy', $po->purchaseorder_ID) }}" onsubmit="return confirm('Are you sure you want to delete this purchase order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500">No purchase orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
