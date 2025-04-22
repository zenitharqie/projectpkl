<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 h-full bg-white shadow-lg p-5 flex flex-col overflow-y-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Admin Panel</h2>
            <ul class="space-y-3 flex-1">
                <li><a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Dashboard</a></li>
                <li><a href="{{ url('/user/inquiryform') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Add Inquiry</a></li>
            <li><a href="{{ url('/admin/listinquiry') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">List Inquiry</a></li>
                {{-- <li><a href="{{ url('/admin/quotation') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Add Quotations</a></li> --}}
                <li><a href="{{ url('/admin/listquotation') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Quotations</a></li>
                <li><a href="{{ url('/') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Purchase Orders</a></li>
                <li><a href="{{ url('/admin/payment') }}" class="flex items-center p-3 hover:bg-gray-200 rounded-md">Payments</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto p-6 flex-1 overflow-y-auto">
            <h1 class="text-3xl font-bold text-center mb-6">Quotation Form</h1>
            <form action="{{ route('quotation.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
                @csrf
                <!-- Customer Details -->
                <div class="mb-6">
                <label for="inquiry_id" class="block text-gray-700">Inquiry ID</label>
                <input type="text" name="inquiry_id" id="inquiry_id" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Customer Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_name" class="block text-gray-700">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div>
                            <label for="company_name" class="block text-gray-700">Company Name</label>
                            <input type="text" name="company_name" id="company_name" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="phone_number" class="block text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                    </div>
                </div>

                <!-- Item Details -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Item Details</h2>
                    <div id="items-container">
                        <div class="item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label for="item_name" class="block text-gray-700">Item Name</label>
                                <input type="text" name="items[0][name]" class="w-full px-4 py-2 border rounded-lg" required>
                            </div>
                            <div>
                                <label for="quantity" class="block text-gray-700">Quantity</label>
                                <input type="number" name="items[0][quantity]" class="w-full px-4 py-2 border rounded-lg" required>
                            </div>
                            <div>
                                <label for="price" class="block text-gray-700">Price</label>
                                <input type="number" name="items[0][price]" class="w-full px-4 py-2 border rounded-lg" required>
                            </div>
                            <div>
                                <label for="total" class="block text-gray-700">Total</label>
                                <input type="number" name="items[0][total]" class="w-full px-4 py-2 border rounded-lg" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-item" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Item</button>
                </div>

                <!-- Business Unit -->
                <div class="mb-6">
                    <label for="business_unit" class="block text-gray-700">Business Unit</label>
                    <select name="business_unit" id="business_unit" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="Bisnis Unit 1 (Penjualan Electrical, Alvalaval)">Bisnis Unit 1 (Penjualan Electrical, Alvalaval)</option>
                        <option value="Bisnis Unit 2 (Jasa Mechanical, Service, Konstruksi)">Bisnis Unit 2 (Jasa Mechanical, Service, Konstruksi)</option>
                        <option value="Bisnis Unit 3 (Olincash)">Bisnis Unit 3 (Olincash)</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" class="w-full px-4 py-2 border rounded-lg" rows="4"></textarea>
                </div>

                <!-- Attachments -->
                <div class="mb-6">
                    <label for="attachment" class="block text-gray-700">Attachment (PDF)</label>
                    <input type="file" name="attachment" id="attachment" class="w-full px-4 py-2 border rounded-lg" accept="application/pdf">
                </div>

                <!-- Summary -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">Summary</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">Subtotal: <span id="subtotal">0</span></p>
                        <p class="text-gray-700">Tax (11%): <span id="tax">0</span></p>
                        <p class="text-gray-700 font-bold">Total: <span id="total-summary">0</span></p>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status_quotation" class="block text-gray-700">Status</label>
                    <select name="status_quotation" id="status_quotation" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="N/A" selected>N/A</option>
                        <option value="VAL">VAL</option>
                        <option value="LOST">LOST</option>
                        <option value="WIP">WIP</option>
                        <option value="AR">AR</option>
                        <option value="CLSD">CLSD</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">Send Quotation</button>
            </form>
        </div>
    </div>

    <script>
        // Add Item
        let itemCount = 1;
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.classList.add('item', 'grid', 'grid-cols-1', 'md:grid-cols-4', 'gap-4', 'mb-4');
            newItem.innerHTML = `
                <div>
                    <label for="item_name" class="block text-gray-700">Item Name</label>
                    <input type="text" name="items[${itemCount}][name]" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label for="quantity" class="block text-gray-700">Quantity</label>
                    <input type="number" name="items[${itemCount}][quantity]" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label for="price" class="block text-gray-700">Price</label>
                    <input type="number" name="items[${itemCount}][price]" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label for="total" class="block text-gray-700">Total</label>
                    <input type="number" name="items[${itemCount}][total]" class="w-full px-4 py-2 border rounded-lg" readonly>
                </div>
            `;
            container.appendChild(newItem);
            itemCount++;
        });

        // Calculate Totals
        document.addEventListener('input', function(e) {
            if (e.target.name && (e.target.name.includes('quantity') || e.target.name.includes('price'))) {
                const item = e.target.closest('.item');
                const quantity = item.querySelector('input[name*="quantity"]').value;
                const price = item.querySelector('input[name*="price"]').value;
                const total = quantity * price;
                item.querySelector('input[name*="total"]').value = total;

                updateSummary();
            }
        });

        function updateSummary() {
            let subtotal = 0;
            document.querySelectorAll('input[name*="total"]').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });
            const tax = subtotal * 0.11;
            const total = subtotal + tax;

            document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('tax').textContent = tax.toFixed(2);
            document.getElementById('total-summary').textContent = total.toFixed(2);
        }
    </script>
</body>
</html>