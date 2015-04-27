<?php
    header("Content-Type:application/json;charset=UTF-8");
    include "../config/config.php";

    $code = $_GET['code'];
    // $code = "20150415140055||A55473DC04F666053926D8DF013A94";

    //参数 code验证
    if(strpos($code, "||") == false){
        echo json_encode(array("status" => false, "msg" => "error code!"));
        exit();
    }

    // $key = token, $value = code
    list($key, $value) = explode("||", $code);
    // 验证token
    if ($value != strtoupper(substr(md5("idhyt". $key ."android"), 1, -1)) ){
        echo json_encode(array("status" => false, "msg" => "error code!"));
        exit();
    }

//    $conn = mysqli_connect("localhost", "root", "", "vul_info");
    $conn = mysqli_connect(
        $DB_CONFIG_UXSS["DB_HOST"],
        $DB_CONFIG_UXSS["DB_USER"],
        $DB_CONFIG_UXSS["DB_PWD"],
        $DB_CONFIG_UXSS["DB_NAME"]
    );

    $select_sql = "select * from uxss where code ='" . $code . "'";

    $data = mysqli_query($conn, $select_sql);
    $result = array();

    if($data && $data->num_rows > 0 ){
        while($row = mysqli_fetch_array($data, MYSQL_ASSOC)) {
            foreach($row as $key => $value){
                if($key != "code" && $key != "id" && $key != "token" && $key != "user_agent" && $key != "date"){
                    $result[$key] = intval($value);
                }
            }
            break;
        }
        echo json_encode(array("status" => true, "msg" => $result));
    }else{
        echo json_encode(array("status" => false, "msg" => "query empty!"));
    }

    mysqli_close($conn);

