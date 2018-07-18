<?php
if(isset($_REQUEST['p_name'])){
  $dbrt='';
  header("Content-Type: application/json; charset=UTF-8");
  include_once($dbrt.'db_faq.php');
  $db = new dbFAQ();
  $con = $db->connect();

  $p_name=$_REQUEST['p_name'];
  $select_project=mysqli_query($con,"select * from faq_projects where project_name='".mysqli_real_escape_string($con,$p_name)."'");
  $pcount=mysqli_num_rows($select_project);
  if($pcount==0){
    $result_arr=array("error"=>"project not found");
  }else{
    while($data_project=mysqli_fetch_assoc($select_project)){
      $p_id=$data_project["project_id"];
      $theme_color=$data_project["theme_color"];
    }
    $result_arr=array("p_id"=>$p_id,"p_name"=>$p_name,"theme_color"=> $theme_color);
    $db->close();
  }
}else{
  $result_arr=array("error"=>"project name empty");
}
$response=json_encode($result_arr,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
echo $response;
?>
