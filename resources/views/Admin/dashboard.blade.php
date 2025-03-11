<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <main class="flex-1 p-6 flex flex-col"> <!-- Menambahkan flex-col untuk mengatur konten secara vertikal -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">Hi, {{ Auth::user()->email }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                </form>
            </div>
        </div>

        <!-- Cards Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-gray-500">
        <h2 class="text-lg font-semibold">Total Inquiries</h2>
        <p class="text-3xl font-bold">{{ $totalInquiries }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
        <h2 class="text-lg font-semibold">Pending</h2>
        <p class="text-3xl font-bold">{{ $pendingInquiries }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
        <h2 class="text-lg font-semibold">Completed</h2>
        <p class="text-3xl font-bold">{{ $completedInquiries }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
        <h2 class="text-lg font-semibold">In Process</h2>
        <p class="text-3xl font-bold">{{ $processInquiries }}</p>
    </div>
</div>

<!-- New Inquiry Boxes -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-6">
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
        <h2 class="text-lg font-semibold">Today Inquiry</h2>
        <p class="text-3xl font-bold">{{ $todayInquiries }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
        <h2 class="text-lg font-semibold"> Month Inquiry</h2>
        <p class="text-3xl font-bold">{{ $thisMonthInquiries }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
        <h2 class="text-lg font-semibold"> Year Inquiry</h2>
        <p class="text-3xl font-bold">{{ $thisYearInquiries }}</p>
    </div>
</div>
        <!-- Grafik Pertumbuhan Inquiry -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Pertumbuhan Total Inquiry</h2>
            <div class="mb-4">
                <label for="timeframe" class="text-gray-700">Jangka Waktu : </label>
                <select id="timeframe" onchange="updateChart()" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="daily">Daily</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <canvas id="inquiryChart"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('inquiryChart').getContext('2d');
            let inquiryChart;

            function renderChart(data, labels) {
                if (inquiryChart) {
                    inquiryChart.destroy(); // Hancurkan grafik sebelumnya jika ada
                }

                inquiryChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Inquiries',
                            data: data,
                            borderColor: 'rgba(54, 162, 235, 1)', // Warna biru untuk garis
                            backgroundColor: 'rgba(54, 162, 235, 0.2)', // Warna biru transparan untuk area di bawah garis
                            borderWidth: 2,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Render grafik awal dengan data harian
            renderChart(@json($dailyInquiries->pluck('count')), @json($dailyInquiries->pluck('date')));

            function updateChart() {
                const timeframe = document.getElementById('timeframe').value;

                if (timeframe === 'daily') {
                    renderChart(@json($dailyInquiries->pluck('count')), @json($dailyInquiries->pluck('date')));
                } else if (timeframe === 'monthly') {
                    renderChart(@json($monthlyInquiries->pluck('count')), @json($monthlyInquiries->pluck('month')));
                } else if (timeframe === 'yearly') {
                    renderChart(@json($yearlyInquiries->pluck('count')), @json($yearlyInquiries->pluck('year')));
                }
            }
        </script>

        <!-- Recent Inquiries -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-8 mb-6"> <!-- Menambahkan margin bawah untuk memberikan ruang -->
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Inquiries</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">ID</th>
                        <th class="p-2">Customer Name</th>
                        <th class="p-2">Item Code</th>
                        <th class="p-2">Quantity</th>
                        <th class="p-2">Due Date</th>
                        <th class="p-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($recentInquiries->isEmpty())
                        <tr>
                            <td colspan="6" class="p-2 text-center text-gray-500">No recent inquiries found.</td>
                        </tr>
                    @else
                        @foreach($recentInquiries as $inquiry)
                            <tr class="border-t">
                                <td class="p-2">{{ $inquiry->id }}</td>
                                <td class="p-2">{{ $inquiry->customer_name }}</td>
                                <td class="p-2">{{ $inquiry->item_code ?? '-' }}</td>
                                <td class="p-2">{{ $inquiry->quantity }}</td>
                                <td class="p-2">{{ $inquiry->due_date }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded {{ 
                                        $inquiry->status == 'completed' ? 'bg-green-100 text-green-700' : 
                                        ($inquiry->status == 'process' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') 
                                    }}">
                                        {{ ucfirst($inquiry->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>