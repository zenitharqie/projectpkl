<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Optional: For icons -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Dashboard Overview -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Dashboard Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>Total Inquiries</div>
                            <div>120</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>Active Quotations</div>
                            <div>40</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>Pending POs</div>
                            <div>10</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>Outstanding Invoices</div>
                            <div>5</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity">
                            <div class="d-flex justify-content-between">
                                <div>New Inquiry from John Doe</div>
                                <div>2 hours ago</div>
                            </div>
                        </div>
                        <div class="activity">
                            <div class="d-flex justify-content-between">
                                <div>Quotation Approved</div>
                                <div>5 hours ago</div>
                            </div>
                        </div>
                        <div class="activity">
                            <div class="d-flex justify-content-between">
                                <div>New PO Received</div>
                                <div>1 day ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

