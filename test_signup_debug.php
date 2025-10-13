<?php
// Simple debug endpoint to test signup POST
header('Content-Type: application/json');
error_log('=== DEBUG TEST ENDPOINT HIT ===');
error_log('Request method: ' . $_SERVER['REQUEST_METHOD']);
error_log('Content-Type: ' . ($_SERVER['CONTENT_TYPE'] ?? 'not set'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents('php://input');
    error_log('Raw input: ' . $rawInput);
    
    $input = json_decode($rawInput, true);
    error_log('Decoded input: ' . print_r($input, true));
    
    echo json_encode([
        'success' => true,
        'message' => 'Debug test successful',
        'received' => $input,
        'server_info' => [
            'method' => $_SERVER['REQUEST_METHOD'],
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'not set',
            'uri' => $_SERVER['REQUEST_URI']
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests are accepted'
    ]);
}
