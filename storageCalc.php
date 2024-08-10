<?php
session_start();
$name = $_SESSION['email'] ?? 'default_user';

// Path to courses folder
$coursesFolderPath = "courses/$name";

// Function to calculate folder size recursively
function calculateFolderSize($folderPath) {
    $totalSize = 0;
    $dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

    foreach ($dir as $file) {
        if ($file->isFile()) {
            $totalSize += $file->getSize();
        }
    }

    return $totalSize;
}

try {
    if (!is_dir($coursesFolderPath)) {
        $folderSize = 0;
        $unit = 'GB';
    } else {
        $folderSize = calculateFolderSize($coursesFolderPath);
        $folderSizeInGB = $folderSize / (1000 * 1000 * 1000); // Convert bytes to gigabytes
        if ($folderSizeInGB < 1) {
            $folderSizeInMB = $folderSize / (1000 * 1000); // Convert bytes to megabytes
            $unit = 'MB'; // Use MB if size is less than 1 GB
            $folderSize = $folderSizeInMB;
        } else {
            $unit = 'GB'; // Use GB if size is 1 GB or more
        }
    }

    echo json_encode(['size' => round($folderSize, 2), 'unit' => $unit]); // Round to 2 decimal places
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
}
?>
