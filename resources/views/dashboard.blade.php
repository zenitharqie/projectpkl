<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            height: 100vh;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px;
            cursor: pointer;
        }
        .sidebar ul li:hover {
            background: #34495e;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li>Home</li>
            <li>Reports</li>
            <li>Settings</li>
        </ul>
    </div>
    <div class="content">
        <h1>Welcome to Dashboard</h1>
        <div class="card">Dashboard Overview</div>
        <div class="card">User Statistics</div>
    </div>
</body>
</html>
