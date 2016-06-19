<?php
namespace Home\Controller;
use Think\Controller;
class RegisiteController extends Controller{
	//注册帐号
	  public function index($fromUsername,$keyword){
	  	
	     $user=M('user');
		    	$data=array(
		    		'username'=>$fromUsername,
		    		'realname'=>$keyword,
		    		);
		    	if($user->add($data)){
		    		$keyword="注册成功".$fromUsername.'-'.$keyword;
		    	}else{
		    		$keyword="注册失败";
		    	}
			return $keyword;
	 }
}


?>