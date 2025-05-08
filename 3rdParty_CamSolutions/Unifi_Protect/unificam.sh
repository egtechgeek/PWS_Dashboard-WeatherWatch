#!/bin/bash

# === Configuration ===
DOWNLOAD_URL="http://IPADDRESSOFIPCAM/snap.jpeg"
NEW_FILENAME="renamed_file.jpeg"

FTP_HOST="ftp.example.com"
FTP_USER="your_username"
FTP_PASS="your_password"
REMOTE_PATH="/upload"  # path on the FTP server

# === Do not edit below this line ===
# === Download the file ===
echo "[*] Downloading file..."
wget -q "$DOWNLOAD_URL" -O temp_download_file
if [ $? -ne 0 ]; then
    echo "[!] Failed to download file."
    exit 1
fi

# === Rename the file ===
mv temp_download_file "$NEW_FILENAME"
echo "[*] File renamed to $NEW_FILENAME"

# === Upload using curl (recommended over ftp) ===
echo "[*] Uploading file to FTP server..."
curl -T "$NEW_FILENAME" --user "$FTP_USER:$FTP_PASS" "ftp://$FTP_HOST$REMOTE_PATH/"
if [ $? -eq 0 ]; then
    echo "[+] Upload successful."
else
    echo "[!] Upload failed."
fi

# === Cleanup (optional) ===
# rm "$NEW_FILENAME"
