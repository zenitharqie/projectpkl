<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice & Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
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
        <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Pembayaran</h1>
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

            <p class="text-gray-600 mb-4">Kelola invoice dan transaksi pembayaran</p>

            <input type="text" class="w-full p-2 border rounded mb-4" placeholder="Cari invoice...">

            <!-- Daftar Invoice -->
            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <div class="flex justify-between border-b pb-2 mb-2">
                    <div>
                        <p class="text-lg font-semibold">#INV-2025001</p>
                        <p class="text-sm text-gray-500">20 Jan 2025</p>
                    </div>
                    <p class="text-lg font-bold">Rp 2.500.000</p>
                </div>
                <div class="flex justify-between">
                    <div>
                        <p class="text-lg font-semibold">#INV-2025002</p>
                        <p class="text-sm text-gray-500">19 Jan 2025</p>
                    </div>
                    <p class="text-lg font-bold">Rp 1.750.000</p>
                </div>
            </div>

            <!-- Filter dan Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h4 class="font-semibold mb-3">Status Pembayaran</h4>
                    <label class="block"><input type="checkbox" class="mr-2">Lunas</label>
                    <label class="block"><input type="checkbox" class="mr-2">Detail</label>
                    <label class="block"><input type="checkbox" class="mr-2">Pending</label>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h4 class="font-semibold mb-3">Metode Pembayaran</h4>
                    <label class="block"><input type="checkbox" class="mr-2">Transfer Bank</label>
                    <label class="block"><input type="checkbox" class="mr-2">E-Wallet</label>
                    <label class="block"><input type="checkbox" class="mr-2">Convenience Store</label>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h4 class="font-semibold mb-3">Statistik Pembayaran</h4>
                    <p>Total Invoice: <span class="font-bold">24</span></p>
                    <p>Lunas: <span class="font-bold text-green-500">18</span></p>
                    <p>Pending: <span class="font-bold text-yellow-500">6</span></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
