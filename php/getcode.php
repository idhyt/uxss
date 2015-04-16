<?php
header("Content-Type:application/json;charset=UTF-8");

$token = $_GET['token'];
// $token = "8efa17e847";
$ua = $_SERVER['HTTP_USER_AGENT'];

if(empty($token) || strlen($token) < 10 ){
	echo json_encode(array("status"=>false ,"msg" => "token不正确"));
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "vul_info");

if (!$conn){
    echo json_encode(array("status" => false, "msg" => "mysql connect error!"));
    exit();
}

$select_sql = "select id,code from uxss where token ='" . $token . "'";
$data = mysqli_query($conn, $select_sql);

if($data && $data->num_rows > 0){
    while($row = mysqli_fetch_array($data, MYSQL_ASSOC)) {
        $code = $row["code"];
        break;
    }

}
// 不存在则创建code
else{
    $code = date("YmdHis")."||".strtoupper(substr(md5("idhyt".date("YmdHis")."android"),1,-1));

    $insert_sql = "insert into uxss (code, token, user_agent) values ('" .$code ."','". $token. "','". $ua  ."')";
    $data = mysqli_query($conn, $insert_sql);
    if (!$data){
        echo json_encode(array("status" => false, "msg" => "mysql error!"));
        exit();
    }
}

mysqli_close($conn);
echo json_encode(array("status"=> true,"code" => $code));

