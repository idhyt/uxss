<?php
    header("Content-Type:application/json;charset=UTF-8");
    include "../config/config.php";

    $code = $_GET['code'];
    $poc_suc = $_GET['suc'];
    $ua = $_SERVER['HTTP_USER_AGENT'];
    //$code = "20150416091712||26C6A3BB27158AFDAF450392D784D2";
    //$poc_suc = "2";
    $poc = array(
            1 => "poc_1",
            2 => "poc_2",
            3 => "poc_3",
            4 => "poc_4",
            5 => "poc_5",
            6 => "poc_6"
        );

    //参数 suc判断
    if(!array_key_exists($poc_suc, $poc)){
        echo json_encode(array("status" => false, "msg" => "error suc!"));
        exit();
    }

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


    $conn = mysqli_connect(
        $DB_CONFIG_UXSS["DB_HOST"],
        $DB_CONFIG_UXSS["DB_USER"],
        $DB_CONFIG_UXSS["DB_PWD"],
        $DB_CONFIG_UXSS["DB_NAME"]
    );

    $select_sql = "select id from uxss where code ='" . $code . "'";
    $data = mysqli_query($conn, $select_sql);

    // 获取id
    $id = 0;
    if($data && $data->num_rows > 0){
        while($row = mysqli_fetch_array($data, MYSQL_ASSOC)) {
            $id = $row["id"];
            break;
        }
    }else{
        echo json_encode(array("status" => false, "msg" => "请刷新后重试!"));
        exit();
    }

    // 更新测试poc状态
    $update_sql = "update uxss set $poc[$poc_suc] = 1 where id = {$id} and code ='" . $code. "'";
    $data = mysqli_query($conn, $update_sql);
    if (!$data){
        echo json_encode(array("status" => false, "msg" => "update error!"));
        exit();
    }


    mysqli_close($conn);
    echo json_encode(array("status" => true, "msg" => "update success!"));
    exit();
