<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>

<body>
<div class="erp-vendor-list" style="padding-left:4em">

    <?php

    //		include("../includes/config.inc");
    include_once("../../wp-config.php");

    // get connection
    $c = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "select vendor_name, www 
				from vendor
				where www is not null
				and status = 1
				order by vendor_name";

    $rs = mysqli_query($c, $sql);

    while ($row = mysqli_fetch_row($rs)) {
        $name = $row[0];
        $url = $row[1];

        if (strpos($url, '://') === FALSE && $url ) {
            $url = 'http://'.$url;
        }

        echo("<p><a href=\"$url\" target=\"_blank\">$name</a></p>");
    }

    // close result
    mysqli_free_result($rs);
    mysqli_close($c);

    ?>


<!-- end #container -->
</div>

</body>
</html>