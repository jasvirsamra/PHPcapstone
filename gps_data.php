<?php
// Simulate GPS data
header('Content-Type: application/json');
echo json_encode([
    'truck_id' => 'T123',
    'location' => '45.4215, -75.6972',
    'status' => 'On Route',
    'estimated_arrival' => '2024-11-28 18:00:00'
]);
?>
