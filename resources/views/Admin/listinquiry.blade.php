<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Inquiries</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen"> <!-- Menambahkan h-screen di sini -->
    <!-- Sidebar -->
    <div class="w-64 h-full bg-white shadow-lg p-5 flex flex-col overflow-y-auto"> <!-- Mengubah h-screen menjadi h-full -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Admin Panel</h2>
        <ul class="space-y-3 flex-1">
            <li><a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Dashboard</a></li>
            <li><a href="{{ url('/user/inquiryform') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Add Inquiry</a></li>
            <li><a href="{{ url('/admin/listinquiry') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">List Inquiry</a></li>
            <li><a href="{{ url('/admin/quotation') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Quotations</a></li>
            <li><a href="{{ url('/') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Purchase Orders</a></li>
            <li><a href="{{ url('/admin/payment') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Payments</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6 overflow-y-auto"> <!-- Menambahkan overflow-y-auto untuk konten utama -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">List Inquiry</h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Hi, {{ Auth::user()->email }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                </form>
            </div>
        </div>
        <main>
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

           <!-- Dropdown Filter -->
<div class="mb-6">
    <form action="{{ url('/admin/listinquiry') }}" method="GET" class="flex items-center space-x-4">
        <!-- Filter Status -->
        <label for="status" class="text-gray-700">Filter by Status:</label>
        <select name="status" id="status" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="process" {{ request('status') == 'process' ? 'selected' : '' }}>Process</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>

        <!-- Filter Tahun -->
        <label for="year" class="text-gray-700">Year:</label>
        <select name="year" id="year" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All</option>
            @for ($i = date('Y'); $i >= 2020; $i--)
                <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>

        <!-- Filter Bulan -->
        <label for="month" class="text-gray-700">Month:</label>
        <select name="month" id="month" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All</option>
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
            @endfor
        </select>

        <!-- Filter Harian -->
        <label for="day" class="text-gray-700">Day:</label>
        <select name="day" id="day" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All</option>
            @for ($i = 1; $i <= 31; $i++)
                <option value="{{ $i }}" {{ request('day') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </form>
</div>

<!-- Filter by ID and Customer Name -->
<div class="mb-6">
    <form action="{{ url('/admin/listinquiry') }}" method="GET" class="flex items-center space-x-4">
        <!-- Filter by ID -->
        <label for="id" class="text-gray-700">Filter by ID:</label>
        <input type="text" name="id" id="id" value="{{ request('id') }}" class="px-2 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-32" placeholder="ID"> <!-- Ukuran lebih kecil -->

        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Filter ID</button> <!-- Tombol filter ID -->

        <!-- Filter by Customer Name -->
        <label for="customer_name" class="text-gray-700">Filter by Customer Name:</label>
        <input type="text" name="customer_name" id="customer_name" value="{{ request('customer_name') }}" class="px-2 py-1 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-32" placeholder="Customer Name"> <!-- Ukuran lebih kecil -->

        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Filter Name</button> <!-- Tombol filter nama pelanggan -->
    </form>
</div>

            <!-- Notifikasi Filter Aktif -->
            @if(request('year') || request('month') || request('day'))
                <div class="mb-4">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        Showing inquiries for 
                        @if(request('year')) {{ request('year') }} @endif
                        @if(request('month')) {{ date('F', mktime(0, 0, 0, request('month'), 10)) }} @endif
                        @if(request('day')) {{ request('day') }} @endif
                    </span>
                </div>
            @endif

            <!-- Tabel Inquiries -->
            <div class="overflow-x-auto">
                <table class="w-full bg-white shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Customer Name</th>
                            <th class="p-3 text-left">Item Code</th>
                            <th class="p-3 text-left">Quantity</th>
                            <th class="p-3 text-left">Due Date</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Document</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquiries as $inquiry)
                            <tr class="border-t" 
                                data-id="{{ $inquiry->id }}"
                                data-customer="{{ $inquiry->customer_name }}"
                                data-item="{{ $inquiry->item_code ?? '-' }}"
                                data-description="{{ $inquiry->description }}"
                                data-quantity="{{ $inquiry->quantity }}"
                                data-due="{{ $inquiry->due_date }}"
                                data-status="{{ $inquiry->status }}"
                                data-created="{{ \Carbon\Carbon::parse($inquiry->created_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}">
                                <td class="p-3">{{ $inquiry->id }}</td>
                                <td class="p-3">{{ $inquiry->customer_name }}</td>
                                <td class="p-3">{{ $inquiry->item_code ?? '-' }}</td>
                                <td class="p-3">{{ $inquiry->quantity }}</td>
                                <td class="p-3">{{ $inquiry->due_date }}</td>
                                <td class="p-3">
                                    <form action="{{ route('inquiries.updateStatus', $inquiry->id) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded {{ 
                                            $inquiry->status == 'completed' ? 'bg-green-100 text-green-700' : 
                                            ($inquiry->status == 'process' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') 
                                        }}">
                                            <option value="pending" {{ $inquiry->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="process" {{ $inquiry->status == 'process' ? 'selected' : '' }}>Process</option>
                                            <option value="completed" {{ $inquiry->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="p-3">
                                    @if($inquiry->document)
                                        <a href="{{ asset('storage/' . $inquiry->document) }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline">Download Document</a>
                                    @else
                                        <span class="text-gray-500">No document</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <button onclick="showDetail({{ $inquiry->id }})" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Detail</button>
                                    <button onclick="confirmDelete({{ $inquiry->id }})" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div id="detailModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white p-5 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-3">Inquiry Details</h2>
            <p><strong>Customer Name:</strong> <span id="detailCustomer"></span></p>
            <p><strong>Item Code:</strong> <span id="detailItemCode"></span></p>
            <p><strong>Description:</strong> <span id="detailDescription"></span></p>
            <p><strong>Quantity:</strong> <span id="detailQuantity"></span></p>
            <p><strong>Due Date:</strong> <span id="detailDueDate"></span></p>
            <p><strong>Date Created (WIB):</strong> <span id="detailCreatedAt"></span></p>
            <button onclick="closeModal()" class="mt-4 px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">Close</button>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function showDetail(id) {
            const row = document.querySelector(`[data-id='${id}']`);
            if (!row) return;

            // Ambil nilai dari atribut data
            const customer = row.dataset.customer;
            const item = row.dataset.item;
            const description = row.dataset.description;
            const quantity = row.dataset.quantity;
            const dueDate = row.dataset.due;
            const createdAt = row.dataset.created;

            // Tampilkan nilai di modal
            document.getElementById("detailCustomer").innerText = customer;
            document.getElementById("detailItemCode").innerText = item;
            document.getElementById("detailDescription").innerText = description;
            document.getElementById("detailQuantity").innerText = quantity;
            document.getElementById("detailDueDate").innerText = dueDate;
            document.getElementById("detailCreatedAt").innerText = createdAt;

            // Tampilkan modal
            document.getElementById("detailModal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("detailModal").classList.add("hidden");
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this inquiry?')) {
                fetch(`/inquiries/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(result => {
                    if (result.status === 200) {
                        // Tampilkan notifikasi
                        let successAlert = document.getElementById("deleteSuccess");
                        successAlert.classList.remove("hidden");

                        // Sembunyikan notifikasi setelah 3 detik
                        setTimeout(() => {
                            successAlert.classList.add("hidden");
                            window.location.reload(); // Reload halaman setelah notifikasi muncul
                        }, 2000);
                    } else {
                        alert(result.body.message || 'Failed to delete inquiry.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    </script>
</body>
</html>