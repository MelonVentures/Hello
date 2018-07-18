<?php
if(isset($_REQUEST['p_name']) && isset($_REQUEST['term'])){
$conrt='';
header("Content-Type: application/json; charset=UTF-8");
include_once($conrt.'db_faq.php');
$db = new dbFAQ();
$con = $db->connect();
date_default_timezone_set('Asia/Calcutta');
$inserted_at=date('Y-m-d H:i:s');
$p_name=$_REQUEST['p_name'];
$select_project=mysqli_query($con,"select * from faq_projects where project_name='".mysqli_real_escape_string($con,$p_name)."'");
while($data_project=mysqli_fetch_assoc($select_project)){
  $p_id=$data_project["project_id"];
}
if(isset($_REQUEST['term'])){
	$search_term=str_replace("|","&",$_REQUEST['term']);
	$search_term=addslashes($search_term);
}else{
	$search_term="undefined";
}

if($search_term!="undefined"){
if(isset($_REQUEST['code'])){
	$code=$_REQUEST['code'];
}else{
	$code="0";
}
$select=mysqli_query($con,"select * from faq_user_search_log where project_id='".$p_id."' and search_term='".mysqli_real_escape_string($con,$search_term)."' and is_response='".$code."'");
$qcount=mysqli_num_rows($select);
if($qcount==0){
  $insert=mysqli_query($con,"insert into faq_user_search_log (search_term,is_response,s_count,project_id,created_at) values('".$search_term."','".$code."','1','".$p_id."','".$inserted_at."')");
  if($insert){
    $code="1";
    $result="updated";
  }else{
    $code="0";
    $result="failed";
  }
}else{
  $update=mysqli_query($con,"update faq_user_search_log set s_count=s_count+1,updated_at='".$inserted_at."' where search_term='".$search_term."' and is_response='".$code."' and project_id='".$p_id."'");
  if($update){
    $code="1";
    $result="updated";
  }else{
    $code="0";
    $result="failed";
  }
}
$db->close();
$result_arr=array("code"=>$code,"response"=>$result);
}
}
else{
  $result_arr=array("error"=>"missing data");
}
$response=json_encode($result_arr,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
echo $response;
?>
