# -UniFi Protect Bridge for PWS_Dashboard


## Instructions

-Starting with a Raspberry Pi running Raspbian or Debian, headless, OpenSSH required to ensure no additional usb devices are present

-Install Prerequisites:		
```
sudo apt update && sudo apt install wget ftp curl fswebcam
```

-Download unificam.sh:		
```
wget https://github.com/egtechgeek/CustomScripts/blob/main/PWS_Dashboard-WeatherWatch/3rdParty_CamSolutions/USB_Cameras/usb_capture.sh
```

-Edit usb_capture.sh:			
```
nano usb_capture.sh
```
In usb_capture.sh, Be sure to populate the following tags:
	FTP_HOST, FTP_USER, FTP_PASS, REMOTE_PATH, IMAGE_NAME, NEW_FILENAME
			
-Test run the script:		
```
./usb_capture.sh
```

-If all goes well, configure usb_capture.sh to run at your desired intervals automatically by using crontab.


## Notes
-This script will require the use of a Raspberry Pi or minimal Debian machine.<br>
-This script will require very basic knowledge to modify it to your needs, such as setting the USB port of your camera (/dev/ttyUSB0 is typically the default)  when no other usb devices are connected, and FTP information for your PWS_Dashboard web server.<br>
-Functionality of this script will be fairly straightforward. It will perform an image capture at the interval you specify in crontab. Followed by an FTP Push to your specified web server in the target directory.<br>
-This should be used in conjunction with with a future updated "usbcam_cron" script for usb-capture.
