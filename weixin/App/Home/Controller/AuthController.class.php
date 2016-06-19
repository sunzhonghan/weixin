<?php
//这是授权的控制器
namespace Home\Controller;
use Think\Controller;
class AuthController extends Controller{
	//登录权限
	/**
	 * [index 获取Code]
	 * @Author   孙忠汉
	 * @DateTime 2016-06-18T18:47:08+0800
	 * @return   [type]                   [description]
	 */
	  public function index(){
	     if (isset($_GET['code'])){
		    $code=$_GET['code'];
		    R('Home/Auth/access_token',array($code));
		    //access_token($code);用方法
		}else{
		    echo "NO CODE";
		   // echo $_SERVER['HTTP_HOST'].U('Home/Auth/Index');
		}
	 }

	 /**
	  * [access_token 获取]
	  * @Author   孙忠汉
	  * @DateTime 2016-06-18T18:47:48+0800
	  * @return   [type]                   [使用code换取access_token]
	  */
	public  function access_token($code){
	 	//echo $code.'<br/>';
	 	$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxb0dfd9c64cc99d2c&secret=7f5420273cd2279bdcf71a5fec8d1dea&code=$code&grant_type=authorization_code";
	 	 $json_string=(file_get_contents($url));
	 	 $obj = json_decode($json_string); //可以使用$obj->name访问对象的属性  
	 	 $access_token=$obj->access_token;
	 	 $refresh_token=$obj->refresh_token;
	 	 //$data=R('Home/Auth/refresh_token',array($refresh_token));//暂时忽略
	 	 R('Home/Auth/get_info',array($code,$access_token));//获取用户信息
	 }
	 /**
	  * [refresh_token 刷新access_token]
	  * @Author   孙忠汉
	  * @DateTime 2016-06-18T19:18:43+0800
	  * @return   [type]                   [官方文档中提到了刷新access_token的功能，但这不是必须要做的，初次使用可以先忽略。]
	  */
	 public function refresh_token($refresh_token){
	 	$url="https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=wxb0dfd9c64cc99d2c&grant_type=refresh_token&refresh_token=$refresh_token";
	 	return file_get_contents($url);
	 }

	 /**
	  * [get_info 使用access_token获取用户信息]
	  * @Author   孙忠汉
	  * @DateTime 2016-06-18T19:24:54+0800
	  * @param    [type]                   $code         [用户的唯一标识]
	  * @param    [type]              $access_token
	  * [网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同]
	  * @return   [type]                                 [description]
	  */
	 public function get_info($code,$access_token){
	 	$url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$code";
	 	$user=file_get_contents($url);
	 	$user = json_decode($user); //可以使用$obj->name访问对象的属性  
	 	/*openid 	用户的唯一标识
		nickname 	用户昵称
		sex 	用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
		province 	用户个人资料填写的省份
		city 	普通用户个人资料填写的城市
		country 	国家，如中国为CN
		headimgurl 	用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空
		privilege 	用户特权信息，json 数组，
		如微信沃卡用户为（chinaunicom）*/

		$openid=$user->openid;
		$nickname=$user->nickname;
		$sex=$user->sex;
		$province=$user->province;
		$city=$user->city;
		$country=$user->country;
		$headimgurl=$user->headimgurl;
		$privilege=$user->privilege;
		$data=array();
		$data['openid']=$openid;
		$data['nickname']=$nickname;
		$data['sex']=$sex;
		$data['city']=$city;
		$data['country']=$country;
		$data['headimgurl']=$headimgurl;
		$data['privilege']=$privilege;
		$this->data=$data;
		$this->display('get_info');

	 }
	
}


?>