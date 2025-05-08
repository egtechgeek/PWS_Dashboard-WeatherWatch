#!/bin/bash

# === Configuration ===
DOWNLOAD_URL="http://IPADDRESSOFIPCAM/snap.jpeg"
NEW_FILENAME="renamed_file.jpeg"

TRANSFER_PROTOCOL="sftp"  # Options: ftp or sftp

FTP_HOST="ftp.example.com"
FTP_USER="your_username"
FTP_PASS="your_password"
REMOTE_PATH="/upload"  # Path on the remote server

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

# === Upload based on protocol ===
echo "[*] Uploading file using $TRANSFER_PROTOCOL..."

case "$TRANSFER_PROTOCOL" in
    ftp)
        curl -T "$NEW_FILENAME" --user "$FTP_USER:$FTP_PASS" "ftp://$FTP_HOST$REMOTE_PATH/"
        ;;
    sftp)
        curl -T "$NEW_FILENAME" --user "$FTP_USER:$FTP_PASS" "sftp://$FTP_HOST$REMOTE_PATH/"
        ;;
    *)
        echo "[!] Unsupported transfer protocol: $TRANSFER_PROTOCOL"
        exit 1
        ;;
esac

if [ $? -eq 0 ]; then
    echo "[+] Upload successful."
else
    echo "[!] Upload failed."
fi

# === Cleanup (optional) ===
# rm "$NEW_FILENAME"
