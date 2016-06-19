<?php
namespace Home\Controller;
use Think\Controller;
class QiandaoController extends Controller{
  public function index(){//$id,$realname,$hy_id
     $user=$_GET['user'];
     $action=$_GET['action'];
     echo "<script>alert('恭喜$user".'在'."$action".'会议中签到成功!'."');</script>";
 }
}



?>