<?php
/**
 * PDF Encryption Generator - Installation Script
 * Place this file in the root directory and run it once
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <title>PDF Encryption Generator - Installation</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid green; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid red; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border: 1px solid blue; margin: 10px 0; }
        h1 { color: #333; }
        pre { background: #f5f5f5; padding: 10px; overflow: auto; }
    </style>
</head>
<body>
<h1>PDF Encryption Generator - Installation</h1>";

$errors = array();
$success = array();

// Check PHP version
echo "<h2>1. Checking PHP Version</h2>";
if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    echo "<div class='success'>✓ PHP Version: " . PHP_VERSION . " (Compatible)</div>";
    $success[] = "PHP version check passed";
} else {
    echo "<div class='error'>✗ PHP Version: " . PHP_VERSION . " (Requires PHP 7.2 or higher)</div>";
    $errors[] = "PHP version too old";
}

// Check required extensions
echo "<h2>2. Checking Required PHP Extensions</h2>";
$required_extensions = array('gd', 'mbstring', 'openssl', 'json');
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<div class='success'>✓ Extension '$ext' is loaded</div>";
    } else {
        echo "<div class='error'>✗ Extension '$ext' is not loaded</div>";
        $errors[] = "Missing extension: $ext";
    }
}

// Check directories
echo "<h2>3. Checking/Creating Directories</h2>";
$directories = array(
    'application/data',
    'application/data/files',
    'application/libraries',
    'application/third_party',
    'application/third_party/tcpdf',
    'application/third_party/fpdi',
    'application/third_party/fpdf'
);

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "<div class='success'>✓ Created directory: $dir</div>";
            $success[] = "Created $dir";
        } else {
            echo "<div class='error'>✗ Failed to create directory: $dir</div>";
            $errors[] = "Cannot create $dir";
        }
    } else {
        echo "<div class='success'>✓ Directory exists: $dir</div>";
    }
    
    // Check if writable
    if (is_writable($dir)) {
        echo "<div class='success'>✓ Directory is writable: $dir</div>";
    } else {
        echo "<div class='error'>✗ Directory is not writable: $dir</div>";
        $errors[] = "$dir is not writable";
    }
}

// Create initial JSON database
echo "<h2>4. Creating JSON Database</h2>";
$json_file = 'application/data/pdfs.json';
if (!file_exists($json_file)) {
    if (file_put_contents($json_file, json_encode(array(), JSON_PRETTY_PRINT))) {
        echo "<div class='success'>✓ Created JSON database: $json_file</div>";
        $success[] = "JSON database created";
    } else {
        echo "<div class='error'>✗ Failed to create JSON database: $json_file</div>";
        $errors[] = "Cannot create JSON database";
    }
} else {
    echo "<div class='info'>ℹ JSON database already exists: $json_file</div>";
}

// Check web server
echo "<h2>5. Web Server Information</h2>";
echo "<div class='info'>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</div>";

if (stripos($_SERVER['SERVER_SOFTWARE'], 'IIS') !== false) {
    echo "<div class='success'>✓ Running on IIS</div>";
    echo "<div class='info'>Make sure URL Rewrite module is installed and web.config is in place</div>";
} elseif (stripos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false) {
    echo "<div class='success'>✓ Running on Apache</div>";
    echo "<div class='info'>Make sure mod_rewrite is enabled and .htaccess is in place</div>";
}

// Check for libraries
echo "<h2>6. Checking Third-Party Libraries</h2>";

// TCPDF
if (file_exists('application/third_party/tcpdf/tcpdf.php')) {
    echo "<div class='success'>✓ TCPDF library found</div>";
} else {
    echo "<div class='error'>✗ TCPDF library not found</div>";
    echo "<div class='info'>Download from: https://github.com/tecnickcom/TCPDF</div>";
    $errors[] = "TCPDF library missing";
}

// FPDI
if (file_exists('application/third_party/fpdi/src/autoload.php')) {
    echo "<div class='success'>✓ FPDI library found</div>";
} else {
    echo "<div class='info'>ℹ FPDI library not found (optional for importing existing PDFs)</div>";
    echo "<div class='info'>Download from: https://github.com/Setasign/FPDI</div>";
}

// Configuration check
echo "<h2>7. Configuration Files</h2>";

if (file_exists('web.config')) {
    echo "<div class='success'>✓ web.config found (for IIS)</div>";
} else {
    echo "<div class='info'>ℹ web.config not found (required for IIS)</div>";
}

if (file_exists('.htaccess')) {
    echo "<div class='success'>✓ .htaccess found (for Apache)</div>";
} else {
    echo "<div class='info'>ℹ .htaccess not found (required for Apache)</div>";
}

// Summary
echo "<h2>Installation Summary</h2>";

if (empty($errors)) {
    echo "<div class='success'>";
    echo "<h3>✓ Installation Successful!</h3>";
    echo "<p>Your PDF Encryption Generator is ready to use.</p>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ol>";
    echo "<li>Delete this install.php file for security</li>";
    echo "<li>Configure application/config/config.php (base_url, encryption_key)</li>";
    echo "<li>Navigate to your application URL</li>";
    echo "<li>Start generating encrypted PDFs!</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h3>✗ Installation Issues Found</h3>";
    echo "<p>Please fix the following issues:</p>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
    echo "</div>";
}

echo "<h3>Success Messages:</h3>";
echo "<ul>";
foreach ($success as $msg) {
    echo "<li>$msg</li>";
}
echo "</ul>";

// Display system info
echo "<h2>System Information</h2>";
echo "<pre>";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Operating System: " . PHP_OS . "\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Current Directory: " . getcwd() . "\n";
echo "Memory Limit: " . ini_get('memory_limit') . "\n";
echo "Max Execution Time: " . ini_get('max_execution_time') . "\n";
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post Max Size: " . ini_get('post_max_size') . "\n";
echo "</pre>";

echo "</body></html>";
?>