<?php
error_reporting(E_ERROR);
$servername = "SERVER_NAME";
$username = "USERNAME";
$password = "PASWORD";
$dbname = "DATABASE_NAME";
$conn = new mysqli($servername, $username, $password, $dbname);
$idlength = 10;
function RandomString($length)
{
    $keys = array_merge(range(0, 9) , range('a', 'z'));

    for ($i = 0;$i < $length;$i++) $key .= $keys[mt_rand(0, count($keys) - 1) ];

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

    $user = $stmt->get_result()
        ->fetch_assoc(); // get the mysqli result and user
    $stmt->close();

    if ($user["TOKEN"] !== $token) die("Invaild Token");
    if (!isset($_POST["url"])) die("Missing url");

    $id = RandomString($idlength);
    $stmt2 = $conn->prepare("INSERT INTO URLS (URL,ID,OWNER)VALUES (?,?,?)");
    $stmt2->bind_param('sss', $_POST["url"], $id,$user["OWNER"]);
    $stmt2->execute();
    die("https://".$_SERVER['SERVER_NAME']."/?w=" . $id);
}
else
{
    if (!isset($_GET["w"]) || $_GET["w"] === "")
    {
        header("Location: https://github.com/Highlyflammable-Tech/php-file-host");
        exit();
    }
    else
    {
        $stmt = $conn->prepare("SELECT * FROM URLS WHERE ID=?");
        $stmt->bind_param('s', $_GET["w"]);
        $stmt->execute();
        $url_data = $stmt->get_result()
            ->fetch_assoc();
        if ($url_data["ID"] !== $_GET["w"]) die("No URL found!");
        else
        {
            header("Location: " . $url_data["URL"]);
            exit();
        }
    }
    $conn->close();
}
?>
