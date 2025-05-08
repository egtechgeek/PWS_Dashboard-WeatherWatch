Instructions are also commented out within the php script. Recommended to open with notepad++

This setup has been tested with Lorex, Uniview, and Reolink NVRs.

Script Requirements
	All PHP versions supported by pwsDashboard, PHP-GD Library   (PHP 8.3 or Newer Recommended)
	
**webcam_cron_noGD.php has the privacy blackout already disabled, and does not require the PHP-GD Library

Step 1  -On the web host where you have pwsDashboard, create a new FTP user.
		-Set the home directory for this user to be a subdirectory inside of where pwsDashboard is installed.
		-If installed in your webroot directory, the home directory for this FTP user should likely be /img/NVR
		 Note down this home directory, You will need it later.

Step 2	NOTE   Some NVRs may not display all of the following configuration options directly in the console. You may need to use
				the NVRs built in web based admin interface.
		-Log in to your DVR or NVR as an Administrator. In the recording section for the channel you wish to use, enable Snapshots.
		-Some NVRs will offer an option after enabling snapshots for "motion" or "continuous". For the purpose of this script's 
		 intended use, you will want to select "continuous".
		
		-Find the FTP connection settings for your NVR, and populate your FTP server's information accordingly.
		-With most NVRs, this is the tab of the settings where you can toggle to upload video backup or only Snapshots.
		 We only need snapshots for the purpose of this script.
		-Your NVR may also offer you the option of how often to upload snapshots via the FTP connection.
		 If it does offer the option, set something between 5 - 30 minutes, and note that down for later.
		 
Step 3  -Browse your web server and ensure that the snapshots are being uploaded correctly, before proceeding.

Step 4	-Open up webcam_cron.php with Notepad++
		-Scroll right on down towards the bottom, line 166
		-Set "known absolute path" for $sourceDir		This is the home directory that you choose in Step 1, 
		 but you must populate the absolute path. See the default value in webcam_cron.php as example.
		
		-Set "known absolute path" for $destinationDir 	This is the directory that the script will output your 
		 .jpg file to, for use in pwsDashboard.   See the default value in webcam_cron.php as example.
		 
		-Set $newName to the desired file name of the output image.
		 If you want to keep your code to a minimum, you can overwrite the default webcam filler image which is /img/camplus.jpg
		 See the default value in webcam_cron.php as example.
		 
Step 5	-Run the script by browsing to it directly in your web browser, and check for errors.
		 If all looks good, Proceed to Step 6.

Step 6	-Set the script to run as a Scheduled Task, or Crontab, depending on your webhost. 
		 At the end of Step 2, you chose how often your NVR should upload Snapshots.
		 Use that as the minimum value for how often this crontab should run.
		 If your NVR uploads every 5 minutes, run the task every 10 minutes, minimum.