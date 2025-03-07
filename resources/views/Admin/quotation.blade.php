<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CSS CDN -->
</head>
<body class="bg-gray-100">
    <!-- Container Utama -->
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md h-screen p-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Admin Panel</h2>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/user/inquiry') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                        <span>Inquiries</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/inquirylist') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                        <span>List Inquiry</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/quotation') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                        <span>Quotations</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                        <span>Purchase Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/payment') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                        <span>Payments</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Konten Utama -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Create New Quotation</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Hi, {{ Auth::user()->email }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Form Quotation -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <!-- Customer Details -->
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Customer Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="customer-name">
                            Customer Name
                        </label>
                        <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="customer-name" type="text" placeholder="Enter customer name">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" type="email" placeholder="Enter email">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                            Phone
                        </label>
                        <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="phone" type="tel" placeholder="Enter phone number">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="company">
                            Company
                        </label>
                        <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="company" type="text" placeholder="Enter company name">
                    </div>
                </div>

                <!-- Items -->
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Items</h2>
                <div class="mb-6">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 mb-4">
                        + Add Item
                    </button>
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Item</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Quantity</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Price</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="px-4 py-2 text-sm text-gray-700">Product A</td>
                                <td class="px-4 py-2 text-sm text-gray-700">2</td>
                                <td class="px-4 py-2 text-sm text-gray-700">$500</td>
                                <td class="px-4 py-2 text-sm text-gray-700">$1,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Attachments -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="attachments">
                        Attachments
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <p class="text-gray-500">Drag and drop files here or <span class="text-blue-500 cursor-pointer">click to browse</span></p>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">
                        Additional Notes
                    </label>
                    <textarea class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="notes" rows="4" placeholder="Enter any additional notes or terms"></textarea>
                </div>

                <!-- Summary -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Summary</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Subtotal</span>
                            <span class="text-gray-900">$1,000.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Tax (10%)</span>
                            <span class="text-gray-900">$100.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700 font-semibold">Total</span>
                            <span class="text-gray-900 font-semibold">$1,100.00</span>
                        </div>
                    </div>
                </div>

                <!-- Send Quotation Button -->
                <button class="w-full px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Send Quotation
                </button>
            </div>
        </div>
    </div>
</body>
</html>
