<?php
if(!empty($_COOKIE['softselect']))
{
    // todo: wire in Drip
	$cookies = explode('&',$_COOKIE['softselect']);
	$user = explode("=",$cookies[3]);
}
else
{
    //	header('Location:../login.php');
    $user = 4590;
}

?>