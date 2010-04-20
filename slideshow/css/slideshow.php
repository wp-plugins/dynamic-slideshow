<?php 
header('Content-Type: text/css; charset: UTF-8'); 
$width = $_REQUEST['width']; 
$height = $_REQUEST['height'];
$image = $_REQUEST['image'];
$max_height = $height + 50;
$Css = "  
#billboard-wrapper {
    height: ".$max_height."px;
    width: ".$width."px;
    margin-top: 0px;
    overflow: hidden;
}

#billboard {
    background: black;
    position: relative;
    z-index: 1;
    width: ".$width."px;
    height: ".$height."px; 
    overflow: hidden;
}

.billboard-item {
    background: url(../images/image-preload-white.gif) no-repeat center center;
    width: 800px;
    height: 270px;
    position: absolute;
    top: 0px;
    overflow: hidden;
}

#billboard-shadow {
    background: url(../../../../wp-content/uploads/".$image.") no-repeat;
    width: 100%;
    height: 115px;
    position: relative;
    top: -64px;
}

#billboard .billboard-item .billboard-shadow-left,#billboard .billboard-item .billboard-shadow-right {
    position: absolute;
    width: 34px;
    height: 270px;
    background: url(../images/billboard-shadow-left.png) repeat-y;
}

#billboard .billboard-item .billboard-shadow-right {
    left: 736px;
    top: 0px;
    background: url(../images/billboard-shadow-right.png) repeat-y;
}

.billboard-description {
    position: absolute;
    top: 270px;
    left: 0px;
    width: 760px;
    height: 20px;
    background: black;
    padding: 20px;
    color: white;
}

.billboard-description a {
    text-decoration: underline;
    color: white;
}
";
echo $Css;
?>
