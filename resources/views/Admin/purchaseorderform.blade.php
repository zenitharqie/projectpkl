<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Purchase Order Form</title>
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
<div class="flex-1 ml-64 p-8">
    <div class="w-full bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Purchase Order Form</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('purchaseorder.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Quotation ID Field -->
                <div class="md:col-span-2">
                    <label for="QID" class="block text-sm font-medium text-gray-700 mb-1">Quotation ID</label>
                    <div class="flex">
                        <input type="text" id="QID" name="QID" value="{{ old('QID') }}" 
                            class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            placeholder="Enter Quotation ID">
                        <button type="button" id="searchQuotation" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Or fill in the customer details below manually</p>
                </div>
            </div>

            <!-- Customer Information Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Customer Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Customer_Name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                        <input type="text" id="Customer_Name" name="Customer_Name" value="{{ old('Customer_Name') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="Email_Customer" class="block text-sm font-medium text-gray-700 mb-1">Email Customer</label>
                        <input type="email" id="Email_Customer" name="Email_Customer" value="{{ old('Email_Customer') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="End_User" class="block text-sm font-medium text-gray-700 mb-1">End User</label>
                        <input type="text" id="End_User" name="End_User" value="{{ old('End_User') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="Business_Unit" class="block text-sm font-medium text-gray-700 mb-1">Business Unit</label>
                        <input type="text" id="Business_Unit" name="Business_Unit" value="{{ old('Business_Unit') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="Sales_Name" class="block text-sm font-medium text-gray-700 mb-1">Sales Name</label>
                        <input type="text" id="Sales_Name" name="Sales_Name" value="{{ old('Sales_Name') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- PO Information Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Purchase Order Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="PO_Date" class="block text-sm font-medium text-gray-700 mb-1">PO Date</label>
                        <input type="date" id="PO_Date" name="PO_Date" value="{{ old('PO_Date') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="Job_Number" class="block text-sm font-medium text-gray-700 mb-1">Job Number</label>
                        <input type="text" id="Job_Number" name="Job_Number" value="{{ old('Job_Number') }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                <div>
                    <label for="Contract_Number" class="block text-sm font-medium text-gray-700 mb-1">Contract Number</label>
                    <input type="text" id="Contract_Number" name="Contract_Number" value="{{ old('Contract_Number') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="po_value" class="block text-sm font-medium text-gray-700 mb-1">PO Value</label>
                    <input type="number" step="0.01" min="0" id="po_value" name="po_value" value="{{ old('po_value') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="Upload_File" class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
                    <div class="flex items-center">
                        <input type="file" id="Upload_File" name="Upload_File" accept=".pdf,.jpg,.jpeg,.png" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">PDF, JPG, JPEG, or PNG (Max 5MB)</p>
                </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-200">
                    <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-save mr-2"></i>Submit Purchase Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            $('#searchQuotation').click(function() {
                var qid = $('#QID').val();
                if (!qid) {
                    alert('Please enter a Quotation ID');
                    return;
                }

                $.ajax({
                    url: '{{ route("purchaseorder.search") }}',
                    method: 'POST',
                    data: {
                        QID: qid,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        // Fill in the customer fields with the retrieved data
                        $('#Customer_Name').val(data.Customer_Name || '');
                        $('#Email_Customer').val(data.Email_Customer || '');
                        $('#End_User').val(data.End_User || '');
                        $('#Business_Unit').val(data.Business_Unit || '');
                        $('#Sales_Name').val(data.Sales_Name || '');
                    },
                    error: function(xhr) {
                        alert('Quotation not found');
                    }
                });
            });
        });
    </script>
</body>
</html>