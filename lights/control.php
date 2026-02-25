<?php
// control.php
header('Content-Type: application/json');

$ip     = $_GET['ip'];
$ch     = $_GET['ch'];
$action = $_GET['turn'];

// Basic security: Ensure it's only hitting local IPs
if (strpos($ip, '192.168.') !== 0) {
    echo json_encode(["status" => "error", "message" => "Invalid IP"]);
    exit;
}

// Set a short timeout so the page doesn't hang if the Shelly is offline
$ctx = stream_context_create(['http' => ['timeout' => 2]]);

$url = "http://$ip/relay/$ch?turn=$action";
$response = @file_get_contents($url, false, $ctx);

if ($response === FALSE) {
    echo json_encode(["status" => "error", "message" => "Shelly Unreachable"]);
} else {
    echo $response; // Return the actual JSON from the Shelly
}
?>