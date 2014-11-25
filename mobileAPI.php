<?php
/*
	A Sample code for Android APP "Remote controller" (Server side).

	Simple implementation using PHP.
	With user authentication, action code, then executes binary and do RF signal sending.

	If you want use RF module too, this demo uses RaspberryPi(RF transmitter installed) with these two library.
		433Utils
			https://github.com/ninjablocks/433Utils
		WiringPi
			https://projects.drogon.net/raspberry-pi/wiringpi

	Android app location :
		https://play.google.com/store/apps/details?id=biz.tedc.unlocker

*/
$binaryPath = '/home/pi/433Utils/RPi_utils/codesend';
$useSudo = true;
$rfCode1 = '7675618';	// Use to switch ON 
$rfCode2 = '1135517';	// Use to switch OFF

$users = [];
$users['test']['pw'] = '1234';
$users['test']['allow'] = ['ping','open','action1','myOwnAction'];
$users['test2']['pw'] = '2234';
$users['test2']['allow'] = ['ping','open'];

$action = $_POST['action'];
$uid = $_POST['uid'];
$pwd = $_POST['pwd'];
/*
Return :
	1 : OK
	2 : Password error
	3 : Action denied
	4 : Argument error
*/
// Authenticate
$allow = 0;
if(!$action || !$uid || !$pwd){
    $allow = 4;
}else{
    if($pwd == $users[$uid]['pw']){
        if(in_array($action, $users[$uid]['allow'])){
	    $allow = 1;
        }else{
            $allow = 3;
        }
    }else{
        $allow = 2;
    }
}
if($allow !== 1){
    echo $allow;
    exit;
}
//var_dump($_POST);
switch($action){
    case 'ping' :
        echo 1;
	break;
    case 'open' :
	echo 1;
	$cmd = "";
	if($useSudo){
	    $cmd .= "sudo ";
	}
	$cmd .= $binaryPath." ";
	$cmd .= $rfCode1." &";
	exec($cmd);
	break;
    case 'action1' :
	echo 1;
	$cmd = "";
	if($useSudo){
	    $cmd .= "sudo ";
	}
	$cmd .= $binaryPath." ";
	$cmd .= $rfCode2." &";;
	exec($cmd);
	break;
    default :
	echo "Action not implemented.";
	break;
}
