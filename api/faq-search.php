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
    }
    $ques_arr=array();
    $select_ques=mysqli_query($con,"select * from faq_qa q,faq_category c where q.category_id=c.id and q.project_id='".$p_id."' and q.is_active='1' and q.is_approved='1'");
    while($get=mysqli_fetch_array($select_ques)){
        $id=$get['q_id'];
        $ques=$get['question'];
        $ans=$get['answer_html'];
        $cat=$get['cat_name'];
        $ques_arr[]=array("id"=> $id,"ques"=> $ques,"ans"=> $ans,"cat"=> $cat);
    }
    $result_arr=array("questions"=> $ques_arr);

    $db->close();
}
}else{
  $result_arr=array("error"=>"project name empty");
}
$response=json_encode($result_arr,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
echo $response;
?>
