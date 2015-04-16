<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <title>uxss测试</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/poc.js"></script>
    </head>

    <body>
        <h2 class="text-center">UXSS 测试</h2>

        <div align="center">
            <button type="button" class="btn btn-primary btn-lg">正在测试</button>
        </div>

        <div class="progress progress-striped active">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60"
                 aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
            <span>0%</span>
            </div>
        </div>
        <hr>
        <div id="testing"></div>

        <script type="text/javascript">

            var emptyIframe = function(){
                for(var i = 1; i <= window.allPocInfo.allPocNum; i++ )  {
                    $("#poc" + i).attr("src","");
                }
            };

            var resultView = function(hasExpArr){
                var viewHtml = "";
                var allPocLength = window.allPocInfo.allPocNum;
                var allPocHtml = window.allPocInfo.allPocHtml;

                var hasExpLength = hasExpArr.length;

                if(hasExpLength == 0){
                    // 未发现漏洞
                    viewHtml = resultViewHtml.replace("<bugpoctitle>", "未发现漏洞，详情如下：")
                    viewHtml = viewHtml.replace("<bugpoccontent>", "使用总共的" + allPocLength + "个poc测试，未发现漏洞存在");
                }else{
                    // 统计漏洞信息
                    var tmpHtml = "";
                    for(var i = 0; i< hasExpLength; i++){
                        tmpHtml += "<p>存在漏洞：" + allPoc[hasExpArr[i]] + "</p>";
                    }
                    viewHtml = resultViewHtml.replace("<bugpoctitle>", "发现了" + hasExpLength + "个漏洞，详情如下：");
                    viewHtml = viewHtml.replace("<bugpoccontent>", tmpHtml);
                }
                viewHtml = viewHtml.replace("<totoalpoctest>", allPocLength.toString());
                viewHtml = viewHtml.replace("<allpoccontent>", allPocHtml);

                $("#testing").html(viewHtml);
                $(".btn-primary").text("测试完成");
            };

            var getResult = function(code){
                $.getJSON("./php/get.php?code=" + code, function(data){
                    // 返回结果
                    var hasExpArr = new Array();
                    var resultArr = new Array();
                    if(data.status == true){
                        resultArr = data.msg;
                        for(i in resultArr){
                            if (resultArr[i] == 1){
                                // 1表示存在漏洞
                                // resultHtml += "<p>存在" + i + "漏洞</p>";
                                hasExpArr.push(i);
                            }
                        }
                    }
                    resultView(hasExpArr);
                    // 清空iframe
                    emptyIframe();
                });
            };

            var startTest = function(testIndex, code){
                if(testIndex == 1){
                    $("#testing").html("<p>开始测试..............</p>")
                }

                if(testIndex > window.allPocInfo.allPocNum ){
                    var tmpHtml = $("#testing").html();
                    tmpHtml += "<p>测试完成..................</p>";
                    $("#testing").html(tmpHtml);
                    clearInterval(window.timer);

                    $(".btn-primary").text("统计结果中.....");

                    // 2秒后取结果
                    // setTimeout("getResult(code)",3000);
                    setTimeout(getResult, 2000, code);
                    return;
                }

                var testHtml = $("#testing").html();
                testHtml += "<p>正在测试 第"+ testIndex + "个payload</p>";
                $("#testing").html(testHtml);

                var iframeSrc = testIndex + ".php?code=" + code;
                $("#poc" + testIndex ).attr("src","./poc/" + iframeSrc);
                // $("#poc").attr("src","./poc/" + iframeSrc);

                var progress = testIndex / window.allPocInfo.allPocNum;
                progress = progress.toFixed(2) * 100 + "%";
                $(".progress-bar").attr("style", "width:" + progress);
                $(".progress-bar").text(progress);
            };

            var start = function(token){
                $.getJSON("./php/getcode.php?token=" + token, function(data){
                    if(data.status == true){
                        var code = data.code;
                        window.pocIndex += 1;
                        startTest(window.pocIndex, code);
                    }else{
                        clearInterval(window.timer);
                        alert(data.msg);
                    }
                });
            };
            // 全局变量
            window.pocIndex = 0;
            // payload信息
            window.allPocInfo = getAllPocInfo();
            // token
            var token = "<?php echo substr(md5('idhyt'.md5(time())), 3,10 );?>";
            window.timer = window.setInterval("start(token)", 1000);

        </script>
        <iframe id="poc" width=0 height=0></iframe>
        <iframe id="poc1" width=0 height=0></iframe>
        <iframe id="poc2" name = "CVE-2014-6041" width=0 height=0></iframe>
        <iframe id="poc3" width=0 height=0></iframe>
        <iframe id="poc4" width=0 height=0></iframe>
        <iframe id="poc5" width=0 height=0></iframe>
        <iframe id="poc6" width=0 height=0></iframe>
    </body>
</html>
    