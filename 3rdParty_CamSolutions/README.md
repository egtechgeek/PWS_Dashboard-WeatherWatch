This is the future location for additional PWS Dashboard scripts that require a dedicated, standalone node to function

# Roadmap

## -USB Camera Capture for PWS_Dashboard (Now Available for Testing)
    This script will require the use of a Raspberry Pi or minimal Debian machine.
    This script will require very basic knowledge to modify it to your needs, such as setting the USB port of your camera (/dev/ttyUSB0 is typically the default when no other usb devices are connected, and FTP information for your PWS_Dashboard web server.
    Functionality of this script will be fairly straightforward. It will perform an image capture at the interval you specify in crontab. Followed by an FTP Push to your specified web server in the target directory.
    This should be used in conjunction with with a future updated "usbcam_cron" script for usb-capture.


## -UniFi Protect Bridge for PWS_Dashboard (Now Available for Testing)
    This script will require that you have full admin access to your Unifi Protect Cameras to modify settings and features.
    This script will only work with Unifi Protect Cameras that support "Enable Anonymous Snapshot".
    This script will require the use of a Raspberry Pi or minimal Debian virtual machine running inside of your local network, and run as crontab.
    This script will require very basic knowledge to modify it to your needs, such as setting the local ip address of the desired camera, and FTP information for your PWS_Dashboard web server.
    Functionality of this script will be fairly straightforward. It will perform a WGET [http://yourcameralocalip/snap.jpeg at the interval you specify in crontab. Followed by an FTP Push to your specified web server in the target directory.
    This should be used in conjunction with with a future "unificam_cron" script for Unifi.

