<?php
if(isset($_REQUEST['p_name']) && isset($_REQUEST['q_id']) && isset($_REQUEST['action'])){
  $dbrt='';
  header("Content-Type: application/json; charset=UTF-8");
  include_once($dbrt.'db_faq.php');
  $db = new dbFAQ();
  $con = $db->connect();
  $p_name=$_REQUEST['p_name'];
  $select_project=mysqli_query($con,"select * from faq_projects where project_name='".mysqli_real_escape_string($con,$p_name)."'");
  while($data_project=mysqli_fetch_assoc($select_project)){
    $p_id=$data_project["project_id"];
  }
  date_default_timezone_set('Asia/Calcutta');
  $inserted_at=date('Y-m-d H:i:s');
  $q_id=$_REQUEST['q_id'];
  $action=$_REQUEST['action'];
  if(isset($_REQUEST['code'])){
    $code=$_REQUEST['code'];
  }else{
    $code="";
  }

  if($q_id!=''){
    $view=0;$helpful=0;$not_helpful=0;
          if($action=='view'){
            $view=1;
            $code='1';
          }else if($action=='helpful'){
            if($code==1){
              $helpful=1;
            }else{
              $not_helpful=1;
            }
          }
          $select=mysqli_query($con,"select * from faq_user_qlog where q_id='".$q_id."' and project_id='".$p_id."'");
          $qcount=mysqli_num_rows($select);
          if($qcount==0){
            $insert=mysqli_query($con,"insert into faq_user_qlog (q_id,view,helpful,not_helpful,project_id,created_at) values('".$q_id."','".$view."','".$helpful."','".$not_helpful."','".$p_id."','".$inserted_at."')");
            if($insert){
              $code="1";
              $result="inserted";
            }else{
              $code="0";
              $result="failed";
            }
          }else{
            $update=mysqli_query($con,"update faq_user_qlog set view=view+".$view.",helpful=helpful+".$helpful.",not_helpful=not_helpful+".$not_helpful.",updated_at='".$inserted_at."' where q_id='".$q_id."' and project_id='".$p_id."'");
            if($update){
              $code="1";
              $result="updated";
            }else{
              $code="0";
              $result="failed";
            }
          }
  }
  $db->close();

  $result_arr=array("code"=>$code,"response"=>$result);
}else{
  $result_arr=array("error"=>"Missing Data");
}
$response=json_encode($result_arr,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
echo $response;
?>
