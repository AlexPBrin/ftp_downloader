# FTP Downloader Script
## Overview
This PHP script enables users to interact with a remote FTP server directly from their web browser. Hosted on a cPanel server, it provides functionalities to save FTP credentials, connect to the FTP server, list available files, select specific files for download, and handle file download conflicts by auto-incrementing existing filenames.

## Features
- **FTP Credential Submission:** Users can input their FTP server details and desired directory path.
- **Save Credentials:** Credentials are temporarily stored for the session.
- **List Files:** Files in the specified directory are listed, excluding directories like . and .., hidden files, and specific system directories like cgi-bin.
- **Select and Download Files:** Users can select multiple files to download. The script checks if the file exists locally and renames it to prevent overwriting.
- **Retry Failed Downloads:** Provides the ability to retry downloads if they fail.

## Installation

1. **Prepare Your Server:**
	- Ensure your cPanel server supports PHP and FTP connections.
  	- You may need to adjust PHP settings to allow FTP functions if they are disabled by default.

2. **Upload Script:**
 	- Upload ftp_downloader.php to your desired directory in your public_html or a specific subdirectory.

3. **Set Permissions:**
	- Make sure the directory where files will be downloaded has the appropriate write permissions for the script to save files.

## Usage
1. **Access the Script:**
	- Navigate to http://yourdomain.com/path_to_script/ftp_downloader.php in your web browser, replacing yourdomain.com and path_to_script with your actual domain and script path.

2. **Enter FTP Details:**
	- Fill in the form with your FTP server information, including server address, FTP username, password, and the directory path you want to access.

3. **List and Select Files:**
	- Submit the form to list available files. Use the checkboxes to select the files you want to download.

4. **Download Files:**
	- Submit your selection. The script will download the selected files to the server, automatically renaming them if they already exist.

## Security Notes
- **Credential Storage:** This script temporarily stores credentials in the session. For enhanced security, consider implementing encryption or secure storage mechanisms.
- **File Handling:** Ensure the directory where files are downloaded does not serve executable files to prevent security risks.
- **Server Configuration:** Regularly update your server and PHP installation to protect against vulnerabilities.

## Troubleshooting
- **FTP Connection Issues:** Check your FTP server details and ensure your hosting provider allows outbound FTP connections.
- **Permission Errors:** Make sure the script has write permissions in the download directory. Check your server's error logs for more details.
