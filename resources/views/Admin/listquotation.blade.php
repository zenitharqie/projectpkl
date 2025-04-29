<!DOCTYPE html>
<html lang="en"><!-- Modal for Quotation Details -->
<div id="detailModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-5 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-3">Quotation Details</h2>
        <p><strong>Customer Name:</strong> <span id="detailCustomer"></span></p>
        <p><strong>Email:</strong> <span id="detailEmail"></span></p>
        <p><strong>Company:</strong> <span id="detailCompany"></span></p>
        <p><strong>Business Unit:</strong> <span id="detailBusinessUnit"></span></p>
        <p><strong>Notes:</strong> <span id="detailNotes"></span></p>
        <p><strong>PDF Attachment:</strong> <a id="detailPdf" href="#" target="_blank" class="text-blue-500 hover:underline">Download PDF</a></p>
        <p><strong>Total:</strong> <span id="detailTotal"></span></p>

        <!-- Item List -->
        <div class="mt-4">
            <h3 class="font-semibold">Items:</h3>
            <ul id="detailItems" class="list-disc pl-5">
                <!-- Items will be populated here -->
            </ul>
        </div>

        <button onclick="closeModal()" class="mt-4 px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">Close</button>
    </div>
</div>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quotation List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .toggle-fields {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-100 flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 h-screen bg-white shadow-lg p-5 flex flex-col fixed">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Admin Panel</h2>
        <ul class="space-y-3 flex-1">
            <li><a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md transition duration-200"><i class="fas fa-tachometer-alt mr-3 text-blue-500"></i>Dashboard</a></li>
            <li><a href="{{ url('/admin/listinquiry') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md transition duration-200"><i class="fas fa-list mr-3 text-blue-500"></i>List Inquiry</a></li>
            <li><a href="{{ url('/admin/listquotation') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md transition duration-200"><i class="fas fa-file-alt mr-3 text-blue-500"></i>Quotations</a></li>
            <li><a href="{{ url('/purchaseorder') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md transition duration-200"><i class="fas fa-file-alt mr-3 text-blue-500"></i>Purchase Order Form</a></li>
            <li><a href="{{ url('/admin/purchaseorderlist') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md transition duration-200"><i class="fas fa-clipboard-list mr-3 text-blue-500"></i>Purchase Order List</a></li>
        </ul>
        <div class="pt-4 border-t border-gray-200">
            <a href="#" class="flex items-center p-3 text-red-500 hover:bg-gray-200 rounded-md transition duration-200">
                <i class="fas fa-sign-out-alt mr-3"></i>Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 ml-64 p-6 overflow-y-auto"> <!-- Added ml-64 (same as sidebar width) -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">List Quotation</h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Hi, {{ Auth::user()->email }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                </form>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <!-- Filter -->
            <form method="GET" action="{{ route('admin.listquotation') }}" class="mb-4 flex space-x-4">
                <input type="text" name="filter_customer" value="{{ request('filter_customer') }}" placeholder="Filter by Customer Name" class="p-2 border rounded w-1/3">
                <select name="filter_status" class="p-2 border rounded w-1/3">
                    <option value="">Filter by Status</option>
                    <option value="N/A" {{ request('filter_status') == 'N/A' ? 'selected' : '' }}>N/A</option>
                    <option value="VAL" {{ request('filter_status') == 'VAL' ? 'selected' : '' }}>VAL</option>
                    <option value="LOST" {{ request('filter_status') == 'LOST' ? 'selected' : '' }}>LOST</option>
                    <option value="WIP" {{ request('filter_status') == 'WIP' ? 'selected' : '' }}>WIP</option>
                    <option value="AR" {{ request('filter_status') == 'AR' ? 'selected' : '' }}>AR</option>
                    <option value="CLSD" {{ request('filter_status') == 'CLSD' ? 'selected' : '' }}>CLSD</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
            </form>

            <!-- Notifikasi Sukses -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Notifikasi Error -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabel Quotation -->
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-3">QID</th>
                            <th class="p-3">InqID</th>
                            <th class="p-3">Customer</th>
                            <th class="p-3">Inq Date</th>
                            <th class="p-3">Due Date</th>
                            <th class="p-3">Quot</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Email Customer</th>
                            <th class="p-3">Sales</th>
                            <th class="p-3">Act</th>
                        </tr>
                    </thead>
                    <tbody id="quotationTable">
    @forelse($quotations as $quotation)
        <tr class="border-t" 
            data-id="{{ $quotation->id }}"
            data-inquiries_id="{{ $quotation->inquiry_id }}"
            data-customer_id="{{ $quotation->customer_id }}"
            data-inquiry_date="{{ $quotation->inquiry_date }}"
            data-due_date="{{ $quotation->due_date }}"
            data-quotation_file="{{ asset('storage/' . $quotation->quotation_file) }}"
            data-status_quotation="{{ $quotation->status_quotation }}"
            data-email_customer="{{ $quotation->email_customer }}"
            data-sales="{{ $quotation->sales }}"
            data-customer="{{ $quotation->customer->name ?? '' }}"
            data-company="{{ $quotation->customer->company ?? '' }}"
            data-business-unit="{{ $quotation->customer->business_unit ?? '' }}"
            >
            <td class="p-3">{{ $quotation->id }}</td>
            <td class="p-3">{{ $quotation->inquiry_id }}</td>
            <td class="p-3">{{ $quotation->customer->name ?? '' }}</td>
            <td class="p-3">{{ $quotation->inquiry_date }}</td>
            <td class="p-3">
                <form action="{{ route('quotations.update-due-date', $quotation->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="date" name="due_date" value="{{ $quotation->due_date ?? '' }}" onchange="this.form.submit()" class="border rounded p-1 w-full">
                </form>
            </td>
            <td class="p-3">
                <a href="{{ route('quotations.download', $quotation->id) }}" target="_blank" class="text-blue-500 hover:underline block mb-1">Download</a>
                <a href="{{ route('quotations.view', $quotation->id) }}" target="_blank" class="text-green-600 hover:underline block">View</a>
            </td>
            <td class="p-3">
                <form action="{{ route('quotations.update-status', $quotation->id) }}" method="POST">
                    @csrf
                    <select name="status" onchange="this.form.submit()" class="border rounded p-1">
                        <option value="N/A" {{ $quotation->status_quotation === 'N/A' ? 'selected' : '' }} style="background-color:#e5e7eb; color:#374151;">N/A</option>
                        <option value="VAL" {{ $quotation->status_quotation === 'VAL' ? 'selected' : '' }} style="background-color:#dbeafe; color:#1e40af;">VAL</option>
                        <option value="LOST" {{ $quotation->status_quotation === 'LOST' ? 'selected' : '' }} style="background-color:#fee2e2; color:#b91c1c;">LOST</option>
                        <option value="WIP" {{ $quotation->status_quotation === 'WIP' ? 'selected' : '' }} style="background-color:#fef3c7; color:#78350f;">WIP</option>
                        <option value="AR" {{ $quotation->status_quotation === 'AR' ? 'selected' : '' }} style="background-color:#fef9c3; color:#78350f;">AR</option>
                        <option value="CLSD" {{ $quotation->status_quotation === 'CLSD' ? 'selected' : '' }} style="background-color:#d1fae5; color:#065f46;">CLSD</option>
                    </select>
                </form>
            </td>
            <td class="p-3">{{ $quotation->email_customer }}</td>
            <td class="p-3">{{ $quotation->sales }}</td>
            <td class="p-3">
                <form action="{{ route('quotations.destroy', $quotation->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1 bg-red-500 text-white rounded">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10" class="text-center p-3">No quotations found.</td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>
    </div>

  <!-- Modal for Quotation Edit -->
<div id="detailModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-5 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-3">Edit Quotation</h2>
        <form id="editQuotationForm" method="POST" action="">
            @csrf
            @method('PUT')
            <p class="mb-4">Edit due date only in the table.</p>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
        </form>
    </div>
</div>

<script>
        function showDetail(id) {
            const row = document.querySelector(`[data-id='${id}']`);
            if (!row) return;

            // Populate form fields
            document.getElementById('customer_name').value = row.getAttribute('data-customer') || '';
            document.getElementById('due_date').value = row.getAttribute('data-due_date') || '';
            document.getElementById('email_customer').value = row.getAttribute('data-email_customer') || '';
            document.getElementById('company').value = row.getAttribute('data-company') || '';
            document.getElementById('business_unit').value = row.getAttribute('data-business-unit') || '';
            document.getElementById('status').value = row.getAttribute('data-status_quotation') || '';
            document.getElementById('notes').value = row.getAttribute('data-notes') || '';
            document.getElementById('pdf_attachment').href = row.getAttribute('data-quotation_file') || '#';
            document.getElementById('pdf_attachment').innerText = 'Download PDF';
            document.getElementById('total').value = row.getAttribute('data-total') || '';

            // Set form action URL
            const form = document.getElementById('editQuotationForm');
            form.action = `/admin/quotations/${row.getAttribute('data-id')}`;

            // Show modal
            document.getElementById('detailModal').classList.remove('hidden');
        }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>

</body>
</html>