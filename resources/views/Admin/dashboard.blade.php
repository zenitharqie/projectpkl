<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>
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
    <main class="flex-1 p-6 pl-72 flex flex-col overflow-y-auto">
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
                <h2 class="text-lg font-semibold">Total Inquiry</h2>
                <p class="text-3xl font-bold">{{ $totalInquiries }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                <h2 class="text-lg font-semibold">Total Quotations</h2>
                <p class="text-3xl font-bold">{{ $totalQuotations }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
                <h2 class="text-lg font-semibold">POs</h2>
                <p class="text-3xl font-bold">{{ $totalPOs }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-pink-500">
                <h2 class="text-lg font-semibold">Expired Quotations</h2>
                <p class="text-3xl font-bold">{{ $expiredQuotations }}</p>
            </div>
        </div>

        <!-- New Inquiry Boxes -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-6">
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                <h2 class="text-lg font-semibold">Today Inquiry</h2>
                <p class="text-3xl font-bold">{{ $todayInquiries }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                <h2 class="text-lg font-semibold">Month Inquiry</h2>
                <p class="text-3xl font-bold">{{ $thisMonthInquiries }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                <h2 class="text-lg font-semibold">Year Inquiry</h2>
                <p class="text-3xl font-bold">{{ $thisYearInquiries }}</p>
            </div>
        </div>

        <!-- Conversion Rate List (UI only) -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Conversion Rate List</h2>
            <p class="text-gray-500">Conversion rate data will be displayed here (logic not implemented).</p>
        </div>

        <!-- Company Growth Selling (UI only) -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Company Growth Selling</h2>
            <p class="text-gray-500">Company growth chart based on inquiry, quotation, and PO will be displayed here (logic not implemented).</p>
        </div>

        <!-- Business Unit List -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Business Units</h2>
            @if($businessUnits->isEmpty())
                <p class="text-gray-500">No business units found.</p>
            @else
                <ul class="list-disc list-inside text-gray-700">
                    @foreach($businessUnits as $unit)
                        <li>{{ $unit }}</li>
                    @endforeach
                </ul>
            @endif
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
            <button id="toggleChart" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-4" onclick="toggleChart()">Hide Chart</button>
            <canvas id="inquiryChart"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('inquiryChart').getContext('2d');
            let inquiryChart;
            let chartVisible = true; // Status visibilitas grafik

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

            function toggleChart() {
                const chartContainer = document.getElementById('inquiryChart');
                const toggleButton = document.getElementById('toggleChart');

                if (chartVisible) {
                    chartContainer.style.display = 'none'; // Sembunyikan grafik
                    toggleButton.innerText = 'Show Chart'; // Ubah teks tombol
                } else {
                    chartContainer.style.display = 'block'; // Tampilkan grafik
                    toggleButton.innerText = 'Hide Chart'; // Ubah teks tombol
                }

                chartVisible = !chartVisible; // Toggle status visibilitas
            }
        </script>

        <!-- Recent Inquiries -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-8 mb-6">
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