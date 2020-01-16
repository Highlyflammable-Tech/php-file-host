<?php
error_reporting(E_ERROR);

$tokens = array("token1_here",
"token2_here");
$sharexdir = "i/"; //File directory
$lengthofstring = 5; //Length of file name
//Random file name generation
function RandomString($length)
{
    $keys = array_merge(range(0, 9) , range('a', 'z'));

    for ($i = 0;$i < $length;$i++)
    {
        $key .= $keys[mt_rand(0, count($keys) - 1) ];
    }
    return $key;
}

//Check for token
if (isset($_POST['secret']))
{
    //Checks if token is valid
    if (in_array($_POST['secret'], $tokens))
    {
        //Prepares for upload
        $filename = RandomString($lengthofstring);
        $target_file = $_FILES["sharex"]["name"];
        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);

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
    else
    {
        //Invalid key
        echo 'Invalid Secret Key';
    }
}
else
{
    $file = $_GET['f'];
    $array_files = scandir(getcwd() . "/i");
    $array_files = array_diff($array_files, [".", ".."]);
    if (in_array($file, $array_files))
    {
        //sending file//
        $send_file = file_get_contents(getcwd() . "/i/" . $file);
        $size = filesize(getcwd() . "/i/" . $file);
        header("Content-Type: image/png");
        header("Content-length: $size");
        echo $send_file;

    }
    elseif ($file === NULL)
    {
        //Warning if no uploaded data.
        echo 'File not found';
    }
    else
    {
        $send_file = file_get_contents(getcwd() . "/i/missing.png");
        $size = filesize(getcwd() . "/i/missing.png");
        header("Content-Type: image/png");
        header("Content-length: $size");
        echo $send_file;
    }
}

?>
