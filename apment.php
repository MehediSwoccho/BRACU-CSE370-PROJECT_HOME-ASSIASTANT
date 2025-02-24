<?php
// Start the session at the very top of the file
session_start();

// Check if user is logged in before proceeding
if (!isset($_SESSION['email'])) {
    die("User not logged in.");
}

// Database connection setup
$conn = new mysqli("localhost", "root", "", "totalhomeassistupdated");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch form data
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = $_POST['service'];
    $sub_service = $_POST['sub_service'];
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];
    $customer_location = $_POST['customer_location'];
    $customer_email = $_SESSION['email'];  // Retrieve email from session

    // Prepare SQL Insert Statement with sub-service
    $stmt = $conn->prepare("INSERT INTO appointments (service_type, sub_service, time, date, cus_name, cus_phone, cus_email, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("ssssssss", $service, $sub_service, $time, $date, $customer_name, $customer_phone, $customer_email, $customer_location);
    
    if ($stmt->execute()) {
        echo("<script>window.location.href = 'congo.html';</script>");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: lightcoral;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header id="header">
        <div class="logo">
            <a href="index2.html">Total <span>Home Assist</span></a>
        </div>
    </header>

<section id="one" class="wrapper style4">
    <div class="container">
        <h2>Book an Appointment</h2>
        <form action="" method="POST">
            <!-- Service Dropdown -->
            <label for="service">Service Type</label>
            <select id="service" name="service" required onchange="updateSubServiceOptions()">
                <option value="">Select Service</option>
                <option value="Fixing">Fixing</option>
                <option value="House Management">House Management</option>
                <option value="Bachelor Services">Bachelor Services</option>
            </select>

            <!-- Sub-service Dropdown -->
            <label for="sub_service">Sub-service</label>
            <select id="sub_service" name="sub_service" required>
                <option value="">Select Sub-service</option>
            </select>

            <label for="date">Preferred Date:</label>
            <input type="date" id="date" name="date" required><br><br>

            <label for="time">Preferred Time:</label>
            <input type="time" id="time" name="time" required><br><br>

            <label for="customer_name">Your Name:</label>
            <input type="text" id="customer_name" name="customer_name" required><br><br>

            <label for="customer_phone">Your Phone Number:</label>
            <input type="text" id="customer_phone" name="customer_phone" required><br><br>

            <label for="customer_location">Your Location:</label>
            <input type="text" id="customer_location" name="customer_location" required><br><br>

            <input type="submit" value="Book Appointment">
        </form>
    </div>

    <!-- JavaScript to handle sub-service selection -->
    <script>
        function updateSubServiceOptions() {
            const service = document.getElementById('service').value;
            const subService = document.getElementById('sub_service');
            subService.innerHTML = ''; // Clear previous options

            // Define sub-services based on the selected service
            let options = [];

            if (service === 'Fixing') {
                options = ['Electrical Fixing', 'Sanitary Fixing'];
            } else if (service === 'House Management') {
                options = ['House Rental Service', 'House Decoration Service'];
            } else if (service === 'Bachelor Services') {
                options = ['Meal', 'Maid'];
            }

            // Add options to the sub-service dropdown
            options.forEach(function(subServiceOption) {
                const opt = document.createElement('option');
                opt.value = subServiceOption;
                opt.innerHTML = subServiceOption;
                subService.appendChild(opt);
            });
        }

        // Restrict past dates in the date picker
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);

        const yyyy = tomorrow.getFullYear();
        const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const dd = String(tomorrow.getDate()).padStart(2, '0');

        const minDate = `${yyyy}-${mm}-${dd}`;
        document.getElementById('date').setAttribute('min', minDate);
    </script>
</section>
</body>
</html>
