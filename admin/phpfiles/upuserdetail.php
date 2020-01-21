<?php
include_once '../classes/config.php';
require_once '../sanitize.php';
$con = mysqli_connect(HOST_NAME,USER_NAME,PASS,DB_NAME) or die(mysqli_error($con));
// mysqli_select_db(DB_NAME,$con);

$userid = isset($_REQUEST['userid'])?$_REQUEST['userid']:'';
$email = isset($_REQUEST['email'])?stripper($_REQUEST['email']):'';
$cctivation = isset($_REQUEST['cctivation'])?$_REQUEST['cctivation']:'0';
$actexpire = isset($_REQUEST['actexpire'])?$_REQUEST['actexpire']:'';
$referer = isset($_REQUEST['referer'])?$_REQUEST['referer']:'';
$source = isset($_REQUEST['source'])?$_REQUEST['source']:'';
$firmtype = isset($_REQUEST['firmtype'])?$_REQUEST['firmtype']:'';
$geeloc = isset($_REQUEST['geeloc'])?$_REQUEST['geeloc']:'';
$activated = isset($_REQUEST['activated'])?$_REQUEST['activated']:'';
$usertypecode = isset($_REQUEST['usertypecode'])?$_REQUEST['usertypecode']:'';
$firmtype = ($firmtype == "Please Select")?'Other':$firmtype;
$geeloc = ($geeloc == "Please Select")?'Other':$geeloc;
$source = ($source == '')?'1':$source;
if (isset($_REQUEST['password'])) {
    $password = sha1($_REQUEST['password']);
}

if ($userid !== '') {
    $sql = "update user set email_address='" . $email . "',activation_code='" . $cctivation . "',activation_expire_date='" . $actexpire . "',referer='" . $referer . "',
		source_id=" . $source . ",firm_type='" . $firmtype . "',geo_location='" . $geeloc . "',activated='" . $activated . "',user_type_code='" . $usertypecode . "'";
    //echo $sql;

    if (isset($password)) {
        $password = sha1($_REQUEST['password']);
        $sql = $sql . ", password='$password'";
    }
    $sql = $sql . " where user_id=" . $userid;
} else {
    if (!isset($password)) {
        $password = sha1("SomethingRandomShouldG0H3r#");
    }
    $sql = "INSERT INTO user (email_address, activation_code, activation_expire_date, referer,
                              source_id, firm_type, geo_location, activated, user_type_code, password)
            VALUES ('$email', $cctivation, '$actexpire', '$referer', 
                    '$source', '$firmtype', '$geeloc', '$activated', '$usertypecode', '$password')";
}

$res = mysqli_query($con, $sql) or die(mysqli_error($con).$sql);
if($res) echo "Successfully updated USER: ".$userid;
else echo "There was an error updating USER:".$userid;
?>