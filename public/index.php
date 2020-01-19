<?php
error_reporting(E_ERROR);

$tokens = array(
    "keys_here"
);
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
            echo 'File upload failed!';
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
        echo 'This is a php file host!';
    }
    else
    {
        die("File not found!");
    }
}
?>
