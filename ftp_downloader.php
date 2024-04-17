<?php
session_start();

// Check if credentials are saved
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $ftp_server = $_POST['ftp_server'];
    $ftp_user_name = $_POST['ftp_user_name'];
    $ftp_user_pass = $_POST['ftp_user_pass'];
    $directory = $_POST['directory'];

    // Save credentials in session for simplicity (not recommended for production)
    $_SESSION['ftp_details'] = compact('ftp_server', 'ftp_user_name', 'ftp_user_pass', 'directory');
}

// FTP connect and list files
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['list_files'])) {
    $ftp_details = $_SESSION['ftp_details'];
    $conn_id = ftp_connect($ftp_details['ftp_server']);
    $login_result = ftp_login($conn_id, $ftp_details['ftp_user_name'], $ftp_details['ftp_user_pass']);

    if (!$conn_id || !$login_result) {
        die("FTP connection has failed!");
    } else {
        echo "Connected to {$ftp_details['ftp_server']}, for user {$ftp_details['ftp_user_name']}.";
    }

    // Get file list
    $files = ftp_nlist($conn_id, $ftp_details['directory']);

    // Filter out undesired entries
    $filtered_files = array_filter($files, function($file) {
        $basename = basename($file);
        // Exclude . and .. directories, hidden files, and specific directories like cgi-bin
        return $basename !== '.' && $basename !== '..' && $basename[0] !== '.' && $basename !== 'cgi-bin';
    });

    echo "<form method='post'>";
    foreach ($filtered_files as $file) {
        echo "<input type='checkbox' name='files[]' value='$file'> $file<br>";
    }
    echo "<input type='submit' name='download' value='Download Selected Files'>";
    echo "</form>";

    ftp_close($conn_id);
}


// Download selected files
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['download'])) {
    $ftp_details = $_SESSION['ftp_details'];
    $conn_id = ftp_connect($ftp_details['ftp_server']);
    $login_result = ftp_login($conn_id, $ftp_details['ftp_user_name'], $ftp_details['ftp_user_pass']);

    if (!$conn_id || !$login_result) {
        die("FTP connection has failed!");
    }

    foreach ($_POST['files'] as $file) {
        $local_file = __DIR__ . '/' . basename($file);
        $i = 1;
        
        // Check if the file exists and rename it if necessary
        while (file_exists($local_file)) {
            $path_parts = pathinfo($local_file);
            $local_file = $path_parts['dirname'] . '/' . $path_parts['filename'] . '_' . $i . '.' . $path_parts['extension'];
            $i++;
        }

        // Download the file
        if (ftp_get($conn_id, $local_file, $file, FTP_BINARY)) {
            echo "Successfully downloaded $file to $local_file<br>";
        } else {
            echo "There was a problem downloading $file<br>";
        }
    }

    ftp_close($conn_id);
}


?>

<form method="post">
    FTP server: <input type="text" name="ftp_server"><br>
    Username: <input type="text" name="ftp_user_name"><br>
    Password: <input type="password" name="ftp_user_pass"><br>
    Directory: <input type="text" name="directory"><br>
    <input type="submit" name="save" value="Save Credentials">
    <input type="submit" name="list_files" value="List Files">
</form>
