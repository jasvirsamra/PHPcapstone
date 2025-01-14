<?php
$host = 'localhost';
$db = 'flextruck';
$user = 'root';  
$pass = '654321';      
$conn = new mysqli($host, $user, $pass, $db);

// Check if connection to database is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_load'])) {
        // Retrieve form data
        $carrier_id = intval($_POST['carrier_id']);
        $customer_name = $_POST['customer_name'];
        $pickup_time = $_POST['pickup_time'];
        $delivery_time = $_POST['delivery_time'];
        $route = $_POST['route'];

        // Call the function to create a new load in the database
        if (createLoad($carrier_id, $customer_name, $pickup_time, $delivery_time, $route)) {
            echo "Load successfully created!";
        } else {
            echo "Error creating load.";
        }
    }
}

// Function to insert the new load into the database
function createLoad($carrier_id, $customer_name, $pickup_time, $delivery_time, $route) {
    global $conn;
    $sql = "INSERT INTO loads (carrier_id, customer_name, pickup_time, delivery_time, route,) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issss', $carrier_id, $customer_name, $pickup_time, $delivery_time, $route);
    return $stmt->execute();
}


/////////////////////////////
// TRUCK MANAGEMENT
/////////////////////////////

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_truck'])) {
    // Retrieve the form data
    $driver_id = intval($_POST['driver_id']);
    $license_plate = $_POST['license_plate'];
    $trailer_id = isset($_POST['trailer_id']) ? intval($_POST['trailer_id']) : NULL;
    $gps_latitude = floatval($_POST['gps_latitude']);
    $gps_longitude = floatval($_POST['gps_longitude']);

    // Function to create a new truck record
    function createTruck($driver_id, $license_plate, $trailer_id, $gps_latitude, $gps_longitude) {
        global $conn;
        $sql = "INSERT INTO trucks (driver_id, license_plate, trailer_id, gps_latitude, gps_longitude) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isidd', $driver_id, $license_plate, $trailer_id, $gps_latitude, $gps_longitude);
        return $stmt->execute();
    }

    // Call the createTruck function to insert data into the database
    if (createTruck($driver_id, $license_plate, $trailer_id, $gps_latitude, $gps_longitude)) {
        echo "<p>Truck record created successfully.</p>";
    } else {
        echo "<p>Error: Could not create truck record.</p>";
    }
}

// Close database connection
$conn->close();

/////////////////////////////
// INVOICE MANAGEMENT
/////////////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create an invoice
    if (isset($_POST['create_invoice'])) {
        $load_id = intval($_POST['load_id']);
        $total_amount = floatval($_POST['total_amount']);
        $issue_date = $_POST['issue_date'];
        $due_date = $_POST['due_date'];

        if (createInvoice($load_id, $total_amount, $issue_date, $due_date)) {
            echo "Invoice created successfully!";
        } else {
            echo "Failed to create invoice.";
        }
    }

    // Update invoice status
    if (isset($_POST['update_status'])) {
        $invoice_id = intval($_POST['invoice_id']);
        $status = $_POST['status'];

        if (updateInvoiceStatus($invoice_id, $status)) {
            echo "Invoice status updated successfully!";
        } else {
            echo "Failed to update invoice status.";
        }
    }
}

// Create an invoice
function createInvoice($load_id, $total_amount, $issue_date, $due_date) {
    global $conn;
    $sql = "INSERT INTO invoices (load_id, total_amount, issue_date, due_date, status) 
            VALUES (?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('idss', $load_id, $total_amount, $issue_date, $due_date);
    return $stmt->execute();
}

// Update invoice status
function updateInvoiceStatus($invoice_id, $status) {
    global $conn;
    $sql = "UPDATE invoices SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $invoice_id);
    return $stmt->execute();
}

/////////////////////////////
// PAYROLL MANAGEMENT
/////////////////////////////

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_payroll'])) {
    // Retrieve the form data
    $driver_id = intval($_POST['driver_id']);
    $total_hours = floatval($_POST['total_hours']);
    $overtime_hours = floatval($_POST['overtime_hours']);
    $bonus = floatval($_POST['bonus']);
    $total_pay = floatval($_POST['total_pay']);
    $pay_date = $_POST['pay_date'];

    // Function to create a new payroll record
    function createPayroll($driver_id, $total_hours, $overtime_hours, $bonus, $total_pay, $pay_date) {
        global $conn;
        $sql = "INSERT INTO payroll (driver_id, total_hours, overtime_hours, bonus, total_pay, pay_date) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iddids', $driver_id, $total_hours, $overtime_hours, $bonus, $total_pay, $pay_date);
        return $stmt->execute();
    }

    // Call the createPayroll function to insert data into the database
    if (createPayroll($driver_id, $total_hours, $overtime_hours, $bonus, $total_pay, $pay_date)) {
        echo "<p>Payroll record created successfully.</p>";
    } else {
        echo "<p>Error: Could not create payroll record.</p>";
    }
}

// Close database connection
$conn->close();


/////////////////////////////
// FUEL EFFICIENCY MONITORING
/////////////////////////////

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track_fuel_efficiency'])) {
    // Retrieve the form data
    $truck_id = intval($_POST['truck_id']);
    $load_id = intval($_POST['load_id']);
    $distance = floatval($_POST['distance']);
    $fuel_used = floatval($_POST['fuel_used']);
    $fuel_cost = floatval($_POST['fuel_cost']);

    // Function to track fuel efficiency
    function trackFuelEfficiency($truck_id, $load_id, $distance, $fuel_used, $fuel_cost) {
        global $conn;
        $sql = "INSERT INTO fuel_efficiency (truck_id, load_id, distance, fuel_used, fuel_cost) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iidd', $truck_id, $load_id, $distance, $fuel_used, $fuel_cost);
        return $stmt->execute();
    }

    // Call the trackFuelEfficiency function to insert data into the database
    if (trackFuelEfficiency($truck_id, $load_id, $distance, $fuel_used, $fuel_cost)) {
        echo "<p>Fuel efficiency record created successfully.</p>";
    } else {
        echo "<p>Error: Could not create fuel efficiency record.</p>";
    }
}

// Close database connection
$conn->close();
?>




