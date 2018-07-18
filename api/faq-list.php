<?php
$dbrt='';
header("Content-Type: application/json; charset=UTF-8");
include_once($dbrt.'db_faq.php');
$db = new dbFAQ();
$con = $db->connect();
if(isset($_REQUEST['p_name'])){
  $p_name=$_REQUEST['p_name'];
  $select_project=mysqli_query($con,"select * from faq_projects where project_name='".mysqli_real_escape_string($con,$p_name)."'");
  $pcount=mysqli_num_rows($select_project);
  if($pcount==0){
    $result_arr=array("error"=>"project not found");
  }else{
    while($data_project=mysqli_fetch_assoc($select_project)){
      $p_id=$data_project["project_id"];
    }
    if(isset($_REQUEST['category'])){
      $category=mysqli_real_escape_string($con,$_REQUEST['category']);
    }else{
      $category="";
    }
  
    if($p_id!=""){
      if($category!=""){
        $select_cat=mysqli_query($con,"select * from faq_category where project_id='".$p_id."' and cat_name='".$category."'");
        $ccount=mysqli_num_rows($select_cat);
        if($ccount==0){
          $result_arr=array("error"=>"category not found");
        }else{
          while($data_cat=mysqli_fetch_assoc($select_cat)){
            $category_id=$data_cat["id"];
            $category=$data_cat["cat_name"];
  
            $select_qa=mysqli_query($con,"select * from faq_qa where category_id='".$category_id."' and project_id='".$p_id."' and is_active='1' and is_approved='1'");
            $qa_array=array();
            while($data=mysqli_fetch_assoc($select_qa)){
              $q_id=$data["q_id"];
              $question=$data["question"];
              $answer=$data["answer_html"];
              $qa_array[]=array("q_id"=>$q_id,"question"=>$question,"answer"=>$answer,"category"=>$category);
            }
            $result_arr=array("faq"=>array(array("category"=> $category,"questions"=> $qa_array)));
          }
        }
          
      }else{
        $select_cat=mysqli_query($con,"select c.id,c.cat_name from faq_category c,faq_qa q where c.id=q.category_id and c.project_id='".$p_id."' and c.is_active='1' and q.is_active='1' and q.is_approved='1' group by c.cat_name order by c.priority asc");
        $cat_arr=array();
          while($row=mysqli_fetch_assoc($select_cat)){
            $cat_arr[]=array("id"=>$row['id'],"name"=> $row['cat_name']);
          }
        $result_arr=array("category"=>$cat_arr);
      }
    }else{
      $result_arr=array("error"=>"project id missing");
    }
  }
  
  

  $db->close();
}else{
  $result_arr=array("error"=>"project name empty");
}
$response=json_encode($result_arr,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
echo $response;

?>
