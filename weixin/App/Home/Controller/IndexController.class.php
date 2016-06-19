<?php
namespace Home\Controller;
use Think\Controller;
import('phpqrcode','App/phpqrcode');
class IndexController extends Controller {
	//登录验证
    public function index(){
    	if (isset($_GET['echostr'])) {
			  R('Check/valid');//第一次验证Key 时调用
			}else{
			  R('Wechat/responseMsg'); 
			}
    }
    //生成二维码
    public function png($fromUsername){
    //要生成二维码的数据
    //查询要签到的地址
    //查询用户名
    $user=M('user');
    $where=array(
    	'username'=>trim($fromUsername),
    	);
    $info=$user->where($where)->find();
    $realname=$info['realname'];
    $text="http://www.yuao188.com".U('Home/Qiandao/Index/',array('user'=>$realname,'action'=>'智慧源动力第二次会议!'),'');
   // $text=R('Home/Qiandao/Index/',array($info['id'],$realname,'1'));//地址改为签到的地址+OpenId
    //$text="http://www.baidu.com";
    //纠错级别， 纠错级别越高，生成图片会越大
    //L水平    7%的字码可被修正
    //M水平    15%的字码可被修正
    //Q水平    25%的字码可被修正
    //H水平    30%的字码可被修正
    $level= "M";
    //图片每个黑点的像素。
    $size= "8";
    $date=date('Y-m-d',time());
    if(!is_dir('./Uploads/qrcode/'.$date)){
    	mkdir('./Uploads/qrcode/'.$date);
    }
     $time=time();
     $path='./Uploads/qrcode/'.$date.'/'.$time.'.png';
     $path1='/Uploads/qrcode/'.$date.'/'.$time.'.png';
     //echo $path;
    //生成图片 第二个参数：是否保存成文件 如需要保存文件，第二个参数改为文件名即可,如：'qrcode.png'
    \QRcode::png($text, $path, $level, $size, 2); 
    return 'http://www.yuao188.com/weixin'.$path1;
    	/*//生成带logo的二维码
  	    $logo = './Uploads/photo/6.jpg';//准备好的logo图片 
		//$path已经生成的原始二维码图 
		if ($logo !== FALSE) { 
		 $QR = imagecreatefromstring(file_get_contents($path)); 
		 $logo = imagecreatefromstring(file_get_contents($logo)); 
		 $QR_width = imagesx($QR);//二维码图片宽度 
		 $QR_height = imagesy($QR);//二维码图片高度 
		 $logo_width = imagesx($logo);//logo图片宽度 
		 $logo_height = imagesy($logo);//logo图片高度 
		 $logo_qr_width = $QR_width / 3.5; 
		 $scale = $logo_width/$logo_qr_width; 
		 $logo_qr_height = $logo_height/$scale; 
		 $from_width = ($QR_width - $logo_qr_width) / 2; 
		 //重新组合图片并调整大小 
		 imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
		 $logo_qr_height, $logo_width, $logo_height); 
		} 
		//输出图片 
		Header("Content-type: image/png");
		ImagePng($QR);
	*/
		//return $path;
    }


}