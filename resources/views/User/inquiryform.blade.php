<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ isset($inquiry) ? 'Edit Inquiry' : 'Create Inquiry' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
   <!-- Sidebar -->
   <div class="w-64 h-full bg-white shadow-lg p-5 flex flex-col overflow-y-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Admin Panel</h2>
        <ul class="space-y-3 flex-1">
            <li><a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Dashboard</a></li>
            <li><a href="{{ url('/admin/listinquiry') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">List Inquiry</a></li>
            <li><a href="{{ url('/admin/quotation') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Add Quotations</a></li>
            <li><a href="{{ url('/admin/listquotation') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Quotations</a></li>
            <li><a href="{{ url('/') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Purchase Orders</a></li>
            <li><a href="{{ url('/admin/payment') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Payments</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
        <!-- App Bar -->
        <header class="bg-white shadow-md p-4">
            <h1 class="text-2xl font-bold text-gray-700">{{ isset($inquiry) ? 'Edit Inquiry' : 'Create Inquiry' }}</h1>
        </header>

        <!-- Form Section -->
        <section class="p-6 overflow-y-auto">
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

            <form action="{{ isset($inquiry) ? route('inquiries.update', $inquiry->id) : route('inquiries.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-6">
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

<!-- Improved Customer Modal -->
<div id="customerModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-xl rounded-lg shadow-xl overflow-hidden">
        <div class="p-4 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Search Customer</h3>
                <button onclick="closeCustomerModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-2">
                <input type="text" id="searchCustomerInput" placeholder="Type customer name or email..." 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       onkeyup="debounceSearch()">
            </div>
        </div>
        <div class="max-h-96 overflow-y-auto">
            <ul id="customerResults" class="divide-y divide-gray-200">
                <li class="p-4 text-center text-gray-500">Type at least 2 characters to search</li>
            </ul>
        </div>
    </div>
</div>

<script>
    // Debounce search function to prevent too many requests
    let searchDebounceTimer;
    function debounceSearch() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(searchCustomer, 300);
    }

    function openCustomerModal() {
        const modal = document.getElementById("customerModal");
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        
        const searchInput = document.getElementById("searchCustomerInput");
        searchInput.value = '';
        searchInput.focus();
        
        // Clear previous results
        document.getElementById('customerResults').innerHTML = 
            '<li class="p-4 text-center text-gray-500">Type at least 2 characters to search</li>';
    }

    function closeCustomerModal() {
        const modal = document.getElementById("customerModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }

    function searchCustomer() {
        const term = document.getElementById('searchCustomerInput').value.trim();
        const resultsContainer = document.getElementById('customerResults');
        
        if (term.length < 2) {
            resultsContainer.innerHTML = 
                '<li class="p-4 text-center text-gray-500">Type at least 2 characters to search</li>';
            return;
        }

        resultsContainer.innerHTML = 
            '<li class="p-4 text-center text-gray-500">Searching customers...</li>';

        fetch(`{{ route('inquiries.search-customers') }}?term=${encodeURIComponent(term)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (!data || data.length === 0) {
                resultsContainer.innerHTML = 
                    '<li class="p-4 text-center text-gray-500">No customers found</li>';
                return;
            }

            const customersHtml = data.map(customer => `
                <li class="p-4 hover:bg-gray-50 cursor-pointer transition-colors"
                    onclick="selectCustomer(${customer.id}, '${escapeHtml(customer.name)}', '${escapeHtml(customer.phone)}', '${escapeHtml(customer.email)}')">
                    <div class="font-medium text-gray-900">${escapeHtml(customer.name)}</div>
                    <div class="text-sm text-gray-500">${escapeHtml(customer.email)}</div>
                    <div class="text-sm text-gray-500">${escapeHtml(customer.phone)}</div>
                </li>
            `).join('');
            
            resultsContainer.innerHTML = customersHtml;
        })
        .catch(error => {
            console.error('Error:', error);
            resultsContainer.innerHTML = `
                <li class="p-4 text-center text-red-500">
                    Error loading customers. Please try again.
                </li>`;
        });
    }

    function selectCustomer(id, name, phone, email) {
        document.getElementById('customer_id').value = id;
        document.getElementById('customer_name').value = name;
        document.getElementById('customer_phone').value = phone;
        document.getElementById('customer_email').value = email;

        // Make fields readonly and grayed out
        ['customer_name', 'customer_phone', 'customer_email'].forEach(id => {
            const input = document.getElementById(id);
            input.readOnly = true;
            input.classList.add('bg-gray-100', 'cursor-not-allowed');
        });

        closeCustomerModal();
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>

</body>
</html>