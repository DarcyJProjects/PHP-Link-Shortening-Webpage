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
$shortenedLength = 4;

//Include "/" at end of url and directory:
$webserver = "https://link.darcyjprojects.xyz/";
$webserverDir = "/var/www/link.darcyjprojects.xyz/";
$password = "PASSWORD";
//------------------------------------------------------
?>

<h1>Link Shortener<br><a href="https://github.com/DarcyJProjects/PHP-Link-Shortening-Webpage" target="_blank" style="font-size: 15px;">Github</a></h1>

<form action="" method="post">
<a>Link: </a><input type="text" name="url" style="width:300px;"><br>
<a>Password: </a><input type="password" name="password"><br><br>
<input type="submit" value="Shorten!">
</form>

<?php
$url = $_POST['url'];

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

        //Create file
        $file = fopen($shortenedFile, "w") or die("Unable to create redirect file!");
        fwrite($file, $fileContent);
        fclose($file);

        //Output
        echo "<a>Success!</a><br>";
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
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
