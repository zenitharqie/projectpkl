<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script>
        function validateFileType(event) {
            const fileInput = event.target;
            const filePath = fileInput.value;
            const allowedExtensions = /\.(pdf)$/i;
            if (!allowedExtensions.exec(filePath)) {
                alert("Only PDF files are allowed!");
                fileInput.value = "";
                return false;
            }
        }

        function validateForm(event) {
            const form = event.target;
            const inputs = form.querySelectorAll("input[required], textarea[required]");
            for (let input of inputs) {
                if (!input.value.trim()) {
                    alert("Please fill out all required fields.");
                    event.preventDefault();
                    return false;
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex">
    <!-- Sidebar -->
<div class="w-64 h-screen bg-white shadow-lg p-5 flex flex-col">
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
    <div class="flex-1 p-6">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Dashboard</h2>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Submit New Inquiry</h3>
            <form action="{{ route('inquiries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="validateForm(event)">
                @csrf
                <div>
                    <label class="block text-gray-700 font-medium">Customer Name</label>
                    <input name="customer_name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" type="text" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Item Code</label>
                    <input name="item_code" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" type="text">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" rows="4" required></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Quantity</label>
                    <input type="number" name="quantity" min="1" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Due Date</label>
                    <input name="due_date" type="date" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Upload Document <span class="text-red-500">(.pdf)</span></label>
                    <input type="file" name="document" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" onchange="validateFileType(event)">
                </div>
                <div>
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300">
                        Submit Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
