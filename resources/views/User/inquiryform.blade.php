<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Inquiry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #6b73ff 0%, #000dff 100%);
        }
        .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-scale:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="gradient-bg shadow-lg p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">CompanyLogo</h1>
                <nav>
                    <a href="#" class="text-white hover:text-gray-200 transition duration-300">Home</a>
                    <a href="#" class="ml-4 text-white hover:text-gray-200 transition duration-300">About</a>
                    <a href="#" class="ml-4 text-white hover:text-gray-200 transition duration-300">Contact</a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 container mx-auto p-6">
            <h2 class="text-4xl font-bold text-gray-800 mb-8 text-center">Submit New Inquiry</h2>

            <!-- Form untuk submit inquiry -->
            <form action="{{ route('inquiries.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-2xl hover-scale">
                @csrf

                <!-- Field: Customer Name -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Customer Name</label>
                    <input name="customer_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" type="text" required>
                </div>

                <!-- Field: Item Code -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Item Code</label>
                    <input name="item_code" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" type="text">
                </div>

                <!-- Field: Description -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea name="description" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" rows="4" required></textarea>
                </div>

                <!-- Field: Quantity -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Quantity</label>
                    <input type="number" name="quantity" id="quantity"
       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
       min="1" required>
                </div>

                <!-- Field: Due Date -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Due Date</label>
                    <input name="due_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" type="date" required>
                </div>

                <!-- Field: Upload Dokumen -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Upload Document</label>
                    <input type="file" name="document" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                </div>

                <!-- Tombol Submit -->
                <div class="mt-8">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
                        Submit Inquiry
                    </button>
                </div>
            </form>
        </main>

        <!-- Footer -->
        <footer class="gradient-bg text-white py-6 mt-8">
            <div class="container mx-auto text-center">
                <p>&copy; 2023 CompanyLogo. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>