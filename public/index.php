<?php
error_reporting(E_ERROR);
$servername = "SERVER_NAME";
$username = "USERNAME";
$password = "PASWORD";
$dbname = "DATABASE_NAME";
$conn = new mysqli($servername, $username, $password, $dbname);

$sharexdir = "i/"; //File directory
$lengthofstring = 5; //Length of file name
//----------------------------------------------------------------//
function RandomString($length)
{
    $keys = array_merge(range(0, 9) , range('a', 'z'));

    for ($i = 0;$i < $length;$i++)  $key .= $keys[mt_rand(0, count($keys) - 1) ];

    return $key;
}
// Check connection
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST['secret']))
{
    $token = $_POST['secret'];
    //sql req
    $stmt = $conn->prepare("SELECT * FROM TOKENS WHERE TOKEN=?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    //error here/
    $result = $stmt->get_result(); // get the mysqli result
    $user = $result->fetch_assoc(); // getting user
        
    $stmt->close();

    if ($user["TOKEN"]!==$token) die("Invaild Token");
    //user is now authenticated
    //Prepares for upload
    $filename = RandomString($lengthofstring);
    $fileType = pathinfo($_FILES["sharex"]["name"], PATHINFO_EXTENSION);
    

    //Accepts and moves to directory
    if (move_uploaded_file($_FILES["sharex"]["tmp_name"], $sharexdir . $filename . '.' . $fileType))
    {
        //Sends info to client
        $json->status = "OK";
        $json->errormsg = "";
        $json->url = $filename . '.' . $fileType;
        //Sends json
        echo (json_encode($json));
    }
    else
    {
        //Warning
        echo 'File upload failed - CHMOD/Folder doesn\'t exist?';
    }
}
else{
    $file = $_GET['f'];
    $file_extension = explode(".", $file) [1];
    $array_files = scandir(getcwd() . "/i");
    $array_files = array_diff($array_files, [".", ".."]);
    if (in_array($file, $array_files))
    {
        //sending file//
        $send_file = file_get_contents(getcwd() . "/i/" . $file);
        $size = filesize(getcwd() . "/i/" . $file);
        if ($file_extension === "png" || $file_extension === "jpeg" || $file_extension === "jpg" || $file_extension === "gif") header("Content-Type: image/$file_extension");
        elseif ($file_extension !== "txt")
        {
            header("Content-Type: application/$file_extension");
            header("Content-disposition: attachment; filename=\"$file\"");
        }
        header("Content-length: $size");
        echo $send_file;

    }
    elseif ($file === NULL)
    {
        //Warning if no uploaded data or file not found
        echo '<a href="https://github.com/Highlyflammable-Tech">Hi this is a php file host</a>';
    }
    else
    {
        die("File Not Found!");

    }
}
?>
