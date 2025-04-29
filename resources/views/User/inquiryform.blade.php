<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ isset($inquiry) ? 'Edit Inquiry' : 'Create Inquiry' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Tambahkan style untuk memastikan modal muncul di atas semua elemen */
        .modal {
            z-index: 1000;
        }
        .modal-content {
            max-height: 80vh;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans flex">
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
<main class="flex-1 p-6 pl-72 flex flex-col overflow-y-auto">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-blue-600 text-white p-4">
            <h1 class="text-2xl font-bold">{{ isset($inquiry) ? 'Edit Inquiry' : 'Create Inquiry' }}</h1>
        </div>

        <!-- Form Section -->
        <div class="p-6">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ isset($inquiry) ? route('inquiries.update', $inquiry->id) : route('inquiries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($inquiry))
                    @method('PUT')
                @endif
                <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id', $inquiry->customer_id ?? '') }}">


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_name" class="block text-gray-700">Customer Name</label>
                        <div class="flex gap-2">
                            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $inquiry->customer->name ?? '') }}"
                                   class="w-full px-4 py-2 border rounded-lg @error('customer_name') border-red-500 @enderror" {{ isset($inquiry) ? 'readonly' : '' }}>
                            <button type="button" onclick="openCustomerModal()"
                                    class="bg-blue-500 text-white px-3 rounded hover:bg-blue-600" {{ isset($inquiry) ? 'disabled' : '' }}>Search</button>
                        </div>
                        @error('customer_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="business_unit" class="block text-gray-700">Business Unit</label>
                        <select id="business_unit" name="business_unit" required
                                class="w-full px-4 py-2 border rounded-lg @error('business_unit') border-red-500 @enderror">
                            <option value="" disabled {{ !isset($inquiry) ? 'selected' : '' }}>Select Business Unit</option>
                            <option value="Business Unit 1" {{ old('business_unit', $inquiry->business_unit ?? '') == 'Business Unit 1' ? 'selected' : '' }}>Business Unit 1 (Penjualan electrical, Alvalaval)</option>
                            <option value="Business Unit 2" {{ old('business_unit', $inquiry->business_unit ?? '') == 'Business Unit 2' ? 'selected' : '' }}>Business Unit 2 (Jasa Mechanical, Service, Konstruksi)</option>
                            <option value="Business Unit 3" {{ old('business_unit', $inquiry->business_unit ?? '') == 'Business Unit 3' ? 'selected' : '' }}>Business Unit 3 (Olincash)</option>
                        </select>
                        @error('business_unit')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-gray-700">Phone Number</label>
                        <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $inquiry->customer->phone ?? '') }}"
                               class="w-full px-4 py-2 border rounded-lg @error('customer_phone') border-red-500 @enderror" {{ isset($inquiry) ? 'readonly' : '' }}>
                        @error('customer_phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_email" class="block text-gray-700">Email</label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email', $inquiry->customer->email ?? '') }}"
                               class="w-full px-4 py-2 border rounded-lg @error('customer_email') border-red-500 @enderror" {{ isset($inquiry) ? 'readonly' : '' }}>
                        @error('customer_email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="sales_name" class="block text-gray-700">Sales Name</label>
                        <input type="text" id="sales_name" name="sales_name" value="{{ old('sales_name', $inquiry->sales_name ?? '') }}"
                               class="w-full px-4 py-2 border rounded-lg @error('sales_name') border-red-500 @enderror">
                        @error('sales_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="end_user" class="block text-gray-700">End User</label>
                        <input type="text" id="end_user" name="end_user" value="{{ old('end_user', $inquiry->end_user ?? '') }}"
                               class="w-full px-4 py-2 border rounded-lg @error('end_user') border-red-500 @enderror">
                        @error('end_user')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="pic_engineering" class="block text-gray-700">PIC Engineering</label>
                        <input type="text" id="pic_engineering" name="pic_engineering" value="{{ old('pic_engineering', $inquiry->pic_engineering ?? '') }}"
                               class="w-full px-4 py-2 border rounded-lg @error('pic_engineering') border-red-500 @enderror">
                        @error('pic_engineering')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email_engineering" class="block text-gray-700">Email Engineering</label>
                        <input type="email" id="email_engineering" name="email_engineering" value="{{ old('email_engineering', $inquiry->email_engineering ?? '') }}"
                               class="w-full px-4 py-2 border rounded-lg @error('email_engineering') border-red-500 @enderror">
                        @error('email_engineering')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3" required
                              class="w-full px-4 py-2 border rounded-lg @error('description') border-red-500 @enderror">{{ old('description', $inquiry->description ?? '') }}</textarea>
                    @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="inquiry_date" class="block text-gray-700">Inquiry Date</label>
                        <input type="date" id="inquiry_date" name="inquiry_date" value="{{ old('inquiry_date', isset($inquiry) ? \Carbon\Carbon::parse($inquiry->inquiry_date)->format('Y-m-d') : '') }}" required
                               class="w-full px-4 py-2 border rounded-lg @error('inquiry_date') border-red-500 @enderror">
                        @error('inquiry_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="document" class="block text-gray-700">Document (optional)</label>
                    <input type="file" id="document" name="document"
                           class="w-full px-4 py-2 border rounded-lg @error('document') border-red-500 @enderror">
                    @if(isset($inquiry) && $inquiry->document)
                        <p class="mt-2 text-sm text-gray-600">Current document: <a href="{{ asset('storage/' . $inquiry->document) }}" target="_blank" class="text-blue-500 underline">Download</a></p>
                    @endif
                    @error('document')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="text-right">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                        {{ isset($inquiry) ? 'Save' : 'Submit Inquiry' }}
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>

<!-- Customer Modal -->
<div id="customerModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden items-center justify-center p-4 modal">
    <div class="bg-white w-full max-w-xl rounded-lg shadow-lg modal-content">
        <div class="p-6 relative">
            <h2 class="text-lg font-semibold mb-2">Search Customer</h2>
            <input type="text" id="searchCustomerInput" placeholder="Type name or email..."
                   class="w-full border px-4 py-2 rounded mb-4" onkeyup="searchCustomer()">
            <ul id="customerResults" class="max-h-64 overflow-y-auto space-y-2"></ul>
            <button onclick="closeCustomerModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-xl">✖</button>
        </div>
    </div>
</div>

<script>
    function openCustomerModal() {
        const modal = document.getElementById("customerModal");
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        const searchInput = document.getElementById("searchCustomerInput");
        searchInput.value = '';
        searchInput.focus();
        searchCustomer();
    }

    function closeCustomerModal() {
        const modal = document.getElementById("customerModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }

    function searchCustomer() {
        const term = document.getElementById('searchCustomerInput').value;
        const resultsContainer = document.getElementById('customerResults');
        
        if (term.length < 2) {
            resultsContainer.innerHTML = '<li class="p-2 text-gray-500">Type at least 2 characters</li>';
            return;
        }

        resultsContainer.innerHTML = '<li class="p-2 text-gray-500">Searching...</li>';

        fetch(`{{ route('inquiries.search-customers') }}?term=${encodeURIComponent(term)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            if (!data || data.length === 0) {
                resultsContainer.innerHTML = '<li class="p-2 text-gray-500">No customers found</li>';
                return;
            }

            const list = data.map(customer => `
                <li class="border p-3 rounded hover:bg-gray-100 cursor-pointer"
                    onclick="selectCustomer(${customer.id}, '${escapeHtml(customer.name)}', '${escapeHtml(customer.phone || '')}', '${escapeHtml(customer.email || '')}')">
                    <strong>${escapeHtml(customer.name)}</strong><br>
                    <small>${customer.email ? escapeHtml(customer.email) : 'No email'}</small><br>
                    <small>${customer.phone ? escapeHtml(customer.phone) : 'No phone'}</small>
                </li>
            `).join('');
            resultsContainer.innerHTML = list;
        })
        .catch(error => {
            console.error('Error:', error);
            resultsContainer.innerHTML = `<li class="p-2 text-red-500">Error: ${escapeHtml(error.message)}</li>`;
        });
    }

    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function selectCustomer(id, name, phone, email) {
        document.getElementById('customer_id').value = id;
        document.getElementById('customer_name').value = name;
        document.getElementById('customer_phone').value = phone;
        document.getElementById('customer_email').value = email;

        ['customer_name', 'customer_phone', 'customer_email'].forEach(id => {
            const input = document.getElementById(id);
            input.readOnly = true;
            input.classList.add('bg-gray-100');
        });

        closeCustomerModal();
    }

    // Close modal when clicking outside
    document.getElementById('customerModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCustomerModal();
        }
    });
</script>

</body>
</html>