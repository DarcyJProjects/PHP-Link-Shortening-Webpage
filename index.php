<style>
h1 {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
a {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
</style>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php
//error_reporting(0);
//CONFIG------------------------------------------------
$shortenedLength = 5;

//Include "/" at end of url and directory:
$webserver = "https://link.darcyjprojects.xyz/";
$webserverDir = "/var/www/link.darcyjprojects.xyz/";
$linklistFile = "/var/www/link.darcyjprojects.xyz/admin/linklist.txt";
$password = "PASSWORD";
//------------------------------------------------------

//Redirect to https (443) if on http (80) protocol
$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
if ($protocol == "http"){
    header('Location: ' . $webserver);
}

?>

<h1>Link Shortener<br><a href="https://github.com/DarcyJProjects/PHP-Link-Shortening-Webpage" target="_blank" style="font-size: 15px;">Github</a></h1>

<form action="" method="post">
<a>Link: </a><input type="text" name="url" style="width:300px;"><br>
<a>Password: </a><input type="password" name="password"><br><br>
<input type="submit" value="Shorten!">
</form>

<?php
$url = $_POST['url'];

//Add https to url if it doesn't contain http or https
$illegal = false;
if (str_contains($url, "https")) {
    $illegal = false;
} else {
    $illegal = true;
}
if (str_contains($url, "http")) {
    $illegal = false;
} else {
    $illegal = true;
}
if ($illegal == true){
    $url = "https://" . $url;
}


if ($_POST['password'] == $password){
    if ($url == NULL){
        //NULL Link Error
        echo "<font color='red'><a>Link cannot be NULL!</a></font><br>";
    } else {
        //Get Variables
        $shortenedName = generateName($shortenedLength);
        $shortenedURL = $webserver . $shortenedName;
        $shortenedFile = $webserverDir . $shortenedName . ".php";
        $fileContent = "<?php\nheader('Location: $url');\nexit;\n?>";
        $file2Content = $shortenedName . " - " . $url . "\n";

        //Save to link list for log
        $file2 = fopen($linklistFile, "a") or die("Unable to write to list file!");
        fwrite($file2, $file2Content);
        fclose($file2);

        //Create file
        $file = fopen($shortenedFile, "w") or die("Unable to create redirect file!");
        fwrite($file, $fileContent);
        fclose($file);

        //Output
        echo "<font color='green'><a>Success!</a></font><br>";
        echo "<b><a>Shortened URL: </a></b><a href='$shortenedURL'>$shortenedURL</a><br>";
        echo "<b><a>Destination: </a></b><a href='$url'>$url</a><br>";
    }

    //REVERT $_POST VALUES
    $_POST = array();
} else if ($_POST['password'] != NULL) {
    //Incorrect Password Error
    echo "<font color='red'><a>Incorrect Password!</a></font><br>";
    if ($url == NULL){
        //NULL Link Error
        echo "<font color='red'><a>Link cannot be NULL!</a></font><br>";
    }

    //REVERT $_POST VALUES
    $_POST = array();
}

//Generate random name with supplied length
function generateName($length) {
    $characters = '0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNOPQRSTUVWXYZ'; //no i, I, l, L to remove confusion
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
