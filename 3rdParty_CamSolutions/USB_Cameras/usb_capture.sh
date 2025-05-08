#!/bin/bash

# === Configuration ===
TRANSFER_PROTOCOL="sftp"  # Options: ftp or sftp
FTP_HOST="ftp.example.com"
FTP_USER="your_username"
FTP_PASS="your_password"
REMOTE_PATH="/upload"  # Remote path for the image

IMAGE_NAME="captured_image.jpg"
NEW_FILENAME="snapshot_$(date +%Y%m%d_%H%M%S).jpg"

# === Do Not Edit Below This Line ===

# === Capture image ===
echo "[*] Capturing image from USB camera..."
fswebcam -r 1280x720 --jpeg 85 -D 1 "$IMAGE_NAME"
if [ $? -ne 0 ]; then
    echo "[!] Failed to capture image."
    exit 1
fi

# === Rename image ===
mv "$IMAGE_NAME" "$NEW_FILENAME"
echo "[*] Image saved as $NEW_FILENAME"

# === Upload ===
echo "[*] Uploading image using $TRANSFER_PROTOCOL..."
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
