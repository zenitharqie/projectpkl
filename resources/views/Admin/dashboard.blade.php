<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                    <a href="{{ url('/user/inquiryform') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
                        <span>Inquiries</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/listinquiry') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
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

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
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

            <!-- Cards Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700">Total Inquiries</h2>
                    <p class="text-3xl font-bold text-gray-900">247</p>
                    <p class="text-sm text-green-500">↑12% from last month</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700">Active Quotations</h2>
                    <p class="text-3xl font-bold text-gray-900">85</p>
                    <p class="text-sm text-green-500">↑8% from last month</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700">Pending POs</h2>
                    <p class="text-3xl font-bold text-gray-900">32</p>
                    <p class="text-sm text-red-500">↓5% from last month</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700">Total Revenue</h2>
                    <p class="text-3xl font-bold text-gray-900">$84,245</p>
                    <p class="text-sm text-green-500">↑18% from last month</p>
                </div>
            </div>

            <!-- Recent Activities & Pending Tasks -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <!-- Recent Activities -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Activities</h2>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-2">
                            <span class="text-blue-500">📄</span>
                            <span>New Inquiry #INQ-2025 - <small class="text-gray-500">2 minutes ago</small></span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <span class="text-purple-500">📜</span>
                            <span>Quotation #QT-1082 Approved - <small class="text-gray-500">1 hour ago</small></span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <span class="text-green-500">💰</span>
                            <span>Payment Received #PAY-789 - <small class="text-gray-500">3 hours ago</small></span>
                        </li>
                    </ul>
                </div>

                <!-- Pending Tasks -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Pending Tasks</h2>
                    <ul class="space-y-2">
                        <li class="p-2 bg-red-100 text-red-700 rounded">🔴 Review Quotation #QT-1085 - Urgent</li>
                        <li class="p-2 bg-orange-100 text-orange-700 rounded">🟠 Follow up PO #PO-456 - Today</li>
                        <li class="p-2 bg-blue-100 text-blue-700 rounded">🔵 Update Payment Status #PAY-790 - Tomorrow</li>
                    </ul>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white p-6 rounded-lg shadow-md mt-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Transactions</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">Transaction ID</th>
                            <th class="p-2">Customer</th>
                            <th class="p-2">Type</th>
                            <th class="p-2">Amount</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="p-2">#TRX-2025-001</td>
                            <td class="p-2">John Smith</td>
                            <td class="p-2">Purchase Order</td>
                            <td class="p-2">$5,240</td>
                            <td class="p-2 text-green-500">Completed</td>
                            <td class="p-2">Jan 15, 2025</td>
                        </tr>
                        <tr class="border-t">
                            <td class="p-2">#TRX-2025-002</td>
                            <td class="p-2">Sarah Johnson</td>
                            <td class="p-2">Quotation</td>
                            <td class="p-2">$3,890</td>
                            <td class="p-2 text-yellow-500">Pending</td>
                            <td class="p-2">Jan 14, 2025</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>