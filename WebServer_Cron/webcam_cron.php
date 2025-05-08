<?php
// A Simple PHP script that can be run in crontab to improve pwsdashboard support with NVR-based camera uploads.
// This script was written to utilize snapshot uploads from a single video channel on a Lorex NVR.
// This script will scan all files and subdirectories within sourceDir for the most recent .jpg file, move the .jpg file to destinationDir, rename the .jpg file, clear the lorex directory so that your web server doesn't get cluttered up, and then using the PHP GD library, black out the bottom 150px of the image to conceal the channel name for privacy. I name my NVR channels using physical address and direction the camera is facing. If you do not want the privacy blackout, you can comment out lines 127 thru 154 from this script.

// Please scroll all the way to the bottom for instructions, and the only 3 variables that you must change.
// Script Begins Below:

// Enable full error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

function moveAndRenameMostRecentJPG($sourceDir, $destinationDir, $newName) {
    echo "🚀 Starting script...\n";

    // Resolve absolute paths
    $sourceDir = realpath($sourceDir);
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0777, true);
    }

    // Debugging: Show resolved paths
    echo "📂 Resolved Source Directory: " . ($sourceDir ? $sourceDir : "Invalid Path") . PHP_EOL;
    echo "📂 Resolved Destination Directory: " . $destinationDir . PHP_EOL;

    // Check if source directory exists
    if (!$sourceDir || !is_dir($sourceDir)) {
        echo "❌ Error: Source directory not found or is invalid: $sourceDir\n";
        return;
    }

    // Ensure destination directory is writable
    if (!is_writable($destinationDir)) {
        echo "❌ Error: Destination directory is not writable: $destinationDir\n";
        return;
    }

    // Variables to track the most recent JPG file
    $mostRecentFile = null;
    $mostRecentTimestamp = 0;

    // Scan for JPG files
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    echo "🔍 Scanning directory for JPG files...\n";

    foreach ($files as $file) {
        if (strtolower($file->getExtension()) === 'jpg') {
            $filePath = $file->getRealPath();
            $fileTimestamp = $file->getMTime();

            echo "📸 Found JPG: $filePath (Modified: " . date("Y-m-d H:i:s", $fileTimestamp) . ")\n";

            if ($fileTimestamp > $mostRecentTimestamp) {
                $mostRecentTimestamp = $fileTimestamp;
                $mostRecentFile = $file;
            }
        }
    }

    // If no JPG files were found, exit
    if (!$mostRecentFile) {
        echo "⚠️ No JPG files found in source directory.\n";
        return;
    }

    // Prepare new file path
    $newFilePath = $destinationDir . DIRECTORY_SEPARATOR . $newName;

    echo "📌 Most recent JPG: " . $mostRecentFile->getRealPath() . PHP_EOL;
    echo "📤 Attempting to move it to: $newFilePath\n";

    // Attempt to move and rename the file
    $fileMoved = false;

    if (rename($mostRecentFile->getRealPath(), $newFilePath)) {
        echo "✅ File moved successfully: $newFilePath\n";
        $fileMoved = true;
    } else {
        echo "⚠️ Rename failed: " . error_get_last()['message'] . "\n";
        echo "Attempting copy instead...\n";

        // Use copy + unlink as a fallback
        if (copy($mostRecentFile->getRealPath(), $newFilePath)) {
            if (unlink($mostRecentFile->getRealPath())) {
                echo "✅ File copied and original deleted successfully.\n";
                $fileMoved = true;
            } else {
                echo "⚠️ File copied, but failed to delete original.\n";
            }
        } else {
            echo "❌ Error: File copy failed.\n";
        }
    }

    // 🔥 Cleanup Step: Delete all files and subdirectories inside $sourceDir
    if ($fileMoved) {
        echo "🔄 Cleaning up $sourceDir...\n";
        deleteContents($sourceDir);
        echo "✅ Cleanup complete.\n";

        // 🖤 Black out bottom 150px of wxcam.jpg
        blackoutBottom($newFilePath, 150);
    } else {
        echo "❌ Cleanup skipped because file move was unsuccessful.\n";
    }
}

// Function to delete all files & subdirectories inside a directory
function deleteContents($dir) {
    $files = array_diff(scandir($dir), ['.', '..']);

    foreach ($files as $file) {
        $filePath = "$dir/$file";
        if (is_dir($filePath)) {
            deleteContents($filePath);
            rmdir($filePath);
        } else {
            unlink($filePath);
        }
    }
}

// 🖤 Function to black out bottom 150px of the image
function blackoutBottom($filePath, $blackoutHeight) {
    echo "🖤 Blacking out bottom $blackoutHeight pixels of image: $filePath\n";

    // Load the image
    $image = imagecreatefromjpeg($filePath);
    if (!$image) {
        echo "❌ Error: Failed to open image.\n";
        return;
    }

    $width = imagesx($image);
    $height = imagesy($image);

    // Create a black rectangle at the bottom
    $black = imagecolorallocate($image, 0, 0, 0);
    imagefilledrectangle($image, 0, $height - $blackoutHeight, $width, $height, $black);

    // Save the modified image
    if (imagejpeg($image, $filePath, 90)) {
        echo "✅ Image modification complete: $filePath\n";
    } else {
        echo "❌ Error: Failed to save modified image.\n";
    }

    // Free memory
    imagedestroy($image);
}

// Example usage & Instructions:

// Please Note: My crontab is set to run this PHP script every 10 minutes, because i've set my NVR to upload image snapshots every 180 seconds. You should set your crontab to whatever you deem appropriate for your NVR upload scenario.

// The FTP Upload feature of the Lorex NVR automatically created a subdirectory of it's IP address inside of the lorex directory. It will then create additional subdirectories by date and channel.

// My web server utilizes the Plesk directory structure of /var/www/vhosts/YOURDOMAIN/YOURSUBDOMAIN/
// You must use the complete "known absolute paths" below for sourceDir and destinationDir as appropriate for your web server scenario.
// If you are using a Lorex NVR, replace NVRIPHERE with your NVR's local IP address.

$sourceDir = '/var/www/vhosts/YOURDOMAIN/YOURSUBDOMAIN/img/NVR/NVRIPHERE'; //Set "known absolute path" for sourceDir as appropriate for your web server scenario.
$destinationDir = '/var/www/vhosts/YOURDOMAIN/YOURSUBDOMAIN/img'; //Set "known absolute path" for destinationDir as appropriate for your web server scenario.
$newName = 'camplus.jpg'; //If you want to change the output file name, do it here

moveAndRenameMostRecentJPG($sourceDir, $destinationDir, $newName);
?>
