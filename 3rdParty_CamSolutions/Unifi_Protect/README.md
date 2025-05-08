# -UniFi Protect Bridge for PWS_Dashboard


## Instructions ( v2 offers SFTP support )

-Starting with a Raspberry Pi running Raspbian or Debian, OpenSSH highly recommended

-Install Prerequisites:		
```
sudo apt update && sudo apt install wget ftp curl
```	

-Download unificam.sh:		
```
wget https://github.com/egtechgeek/CustomScripts/blob/main/PWS_Dashboard-WeatherWatch/3rdParty_CamSolutions/Unifi_Protect/unificam.sh
```

-Edit unificam.sh:			
```
nano unificam.sh
```
In unificam.sh, Be sure to populate the following tags:
DOWNLOAD_URL, NEW_FILENAME, FTP_HOST, FTP_USER, FTP_PASS, REMOTE_PATH
			
-Test run the script:		
```
./unificam.sh
```

-If all goes well, configure unificam.sh to run at your desired intervals automatically by using crontab.


## Notes
-This script will require that you have full admin access to your Unifi Protect Cameras to modify settings and features.<br>
-This script will only work with Unifi Protect Cameras that support "Enable Anonymous Snapshot".<br>
-This script will require the use of a Raspberry Pi or minimal Debian virtual machine running inside of your local network, and run as crontab.<br>
-This script will require very basic knowledge to modify it to your needs, such as setting the local ip address of the desired camera, and FTP information for your PWS_Dashboard web server.<br>
-Functionality of this script is fairly straightforward. It will perform a WGET [http://yourcameralocalip/snap.jpeg at the interval you specify in crontab. Followed by an FTP Push to your specified web server, overwriting any existing file of the same "snap.jpeg" name in the target directory.<br>
-This should be used in conjunction with with a future updated "webcam_cron" script for Unifi.<br>
	
