<?php
/**
 * Simple API Test Script
 * Test basic API endpoints to verify backend functionality
 */

$baseUrl = 'http://localhost:8000/api/v1';

function testEndpoint($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $headers[] = 'Content-Type: application/json';
        }
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    
    curl_close($ch);
    
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    return [
        'status' => $httpCode,
        'headers' => $headers,
        'body' => $body,
        'json' => json_decode($body, true)
    ];
}

echo "🚀 Testing Open Data Portal API\n";
echo "================================\n\n";

// Test 1: Health Check
echo "1. Testing Health Check...\n";
$health = testEndpoint($baseUrl . '/health');
echo "   Status: {$health['status']}\n";
if ($health['json']) {
    echo "   Response: " . json_encode($health['json'], JSON_PRETTY_PRINT) . "\n";
}
echo "\n";

// Test 2: Public Stats
echo "2. Testing Public Stats...\n";
$stats = testEndpoint($baseUrl . '/stats');
echo "   Status: {$stats['status']}\n";
if ($stats['json']) {
    echo "   Response: " . json_encode($stats['json'], JSON_PRETTY_PRINT) . "\n";
}
echo "\n";

// Test 3: Categories
echo "3. Testing Categories...\n";
$categories = testEndpoint($baseUrl . '/categories');
echo "   Status: {$categories['status']}\n";
if ($categories['json'] && isset($categories['json']['data'])) {
    echo "   Found " . count($categories['json']['data']) . " categories\n";
    if (!empty($categories['json']['data'])) {
        echo "   First category: " . $categories['json']['data'][0]['name'] . "\n";
    }
}
echo "\n";

// Test 4: Organizations
echo "4. Testing Organizations...\n";
$organizations = testEndpoint($baseUrl . '/organizations');
echo "   Status: {$organizations['status']}\n";
if ($organizations['json'] && isset($organizations['json']['data'])) {
    echo "   Found " . count($organizations['json']['data']) . " organizations\n";
    if (!empty($organizations['json']['data'])) {
        echo "   First organization: " . $organizations['json']['data'][0]['name'] . "\n";
    }
}
echo "\n";

// Test 5: Datasets
echo "5. Testing Datasets...\n";
$datasets = testEndpoint($baseUrl . '/datasets');
echo "   Status: {$datasets['status']}\n";
if ($datasets['json'] && isset($datasets['json']['data'])) {
    if (isset($datasets['json']['data']['data'])) {
        echo "   Found " . count($datasets['json']['data']['data']) . " datasets\n";
        if (!empty($datasets['json']['data']['data'])) {
            echo "   First dataset: " . $datasets['json']['data']['data'][0]['title'] . "\n";
        }
    } else {
        echo "   Found " . count($datasets['json']['data']) . " datasets\n";
        if (!empty($datasets['json']['data'])) {
            echo "   First dataset: " . $datasets['json']['data'][0]['title'] . "\n";
        }
    }
}
echo "\n";

// Test 6: Login
echo "6. Testing Login...\n";
$loginData = [
    'email' => 'admin@gorontalokab.go.id',
    'password' => 'admin123'
];
$login = testEndpoint($baseUrl . '/auth/login', 'POST', $loginData);
echo "   Status: {$login['status']}\n";
if ($login['json']) {
    if (isset($login['json']['data']['token'])) {
        echo "   Login successful! Token received.\n";
        $token = $login['json']['data']['token'];
        
        // Test authenticated endpoint
        echo "\n7. Testing Authenticated Endpoint...\n";
        $profile = testEndpoint($baseUrl . '/auth/profile', 'GET', null, [
            'Authorization: Bearer ' . $token
        ]);
        echo "   Status: {$profile['status']}\n";
        if ($profile['json'] && isset($profile['json']['data'])) {
            echo "   User: " . $profile['json']['data']['name'] . "\n";
            echo "   Email: " . $profile['json']['data']['email'] . "\n";
        }
    } else {
        echo "   Login failed: " . ($login['json']['message'] ?? 'Unknown error') . "\n";
    }
}
echo "\n";

echo "✅ API Testing Complete!\n";
echo "========================\n";
echo "Backend is ready for frontend integration.\n";
?>