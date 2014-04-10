<?php
ob_start();
session_start();
require 'openid.php';
require './vars.php';
$api_key = $API_KEY;

function logoutbutton() {
    echo "<form action=\"steamauth/logout.php\" method=\"post\"><input class=\"logout\" value=\"Logout\" type=\"submit\" /></form>"; //logout button
}

function steamlogin()
{
try {
    // Change 'localhost' to your domain name.
    $openid = new LightOpenID('http://www.fluidcoding.com');
    if(!$openid->mode) {
        if(isset($_GET['login'])) {
            $openid->identity = 'http://steamcommunity.com/openid';
            header('Location: ' . $openid->authUrl());
        }
 }

     elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
        if($openid->validate()) { 
                $id = $openid->identity;
                $ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                preg_match($ptn, $id, $matches);
              
                session_start();
                $_SESSION['steamid'] = $matches[1]; 
                
                 header('Location: '.$_SERVER['REQUEST_URI']);
                 
        } else {
                echo "User is not logged in.\n";
        }

    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
}

