<?php
namespace Home\Controller;
use Think\Controller;
class WechatController extends Controller {
	public function index(){
		echo 11;
	}
    public function responseMsg()
    {
        // 获得发来的消息
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr,'SimpleXMLElement', LIBXML_NOCDATA);
            $MSG_TYPE = trim($postObj->MsgType);     
            switch ($MSG_TYPE) {
                case "event"://事件
                    $resultStr = $this->handleEvent($postObj);
                    break;
                case "text"://文本
                    $resultStr = $this->handleText($postObj);
                    break;
                case "image"://图片
                    $resultStr = $this->handleImage($postObj);
                    break;
                case "voice"://语音
                    $resultStr = $this->handleVoice($postObj);
                    break;
                default:
                    $resultStr = "Unknow message type: " . $MSG_TYPE;
                    break;
            }

            echo $resultStr;
        } else {
            echo "";
            exit();
        }
    }
    
    //事件回复
    public  function handleEvent($postObj){
      // $url=$_SERVER['HTTP_HOST'].U('Home/Auth/Index');后期改变url
       $content="点击授权<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxb0dfd9c64cc99d2c&redirect_uri=http://www.yuao188.com/weixin1/index.php/Home/auth/index&response_type=code&scope=snsapi_userinfo&state=1
       #wechat_redirect'>点击这里绑定</a>汉哥哥工作室";
        $textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
					</xml>";
        $msgType="text";
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $time = time();
        return  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $content);
    }
    //声音回复
    public function handleVoice($postObj){
        $textTpl="
                <xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
				</xml>";
        //获取语音ID
        $msgType="text";
        $mediaID = trim($postObj->MediaId);//语音ID
        $format = trim($postObj->Format);//格式化
        $Recognition=trim($postObj->Recognition);//语音的内容
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $time = time();//创建时间
        /*if($Recognition=='IP'){
            $content = file_get_contents("http://i.itpk.cn/api.php?question=123&api_key=65e8258201657f87e90ea4f0c84c5491&api_secret=lbks8juyi9rk&question=ip");
        }else{
            $content = file_get_contents("http://i.itpk.cn/api.php?question=123&api_key=65e8258201657f87e90ea4f0c84c5491&api_secret=lbks8juyi9rk&question=".$Recognition);
        }*///智能机器人
  return  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$Recognition);
    }
    //回复文本信息
	public  function handleText($postObj){
	    $textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
					</xml>";
	    $msgType="text";
	    $fromUsername = $postObj->FromUserName;
	    $toUsername = $postObj->ToUserName;
	    $keyword = trim($postObj->Content);
	    $time = time();
	    //查询是否是新用户
	    $user=M('user');
		    $where=array(
		    	'username'=>trim($fromUsername),
		    	);
		    $info=$user->where($where)->find();
		    if(empty($info)){
		    	//新用户注册 不是新用户直接进行下一步
				$keyword=R('Home/Regisite/Index',array(trim($fromUsername),$keyword),'');
		    }else{
		    	$keyword=$keyword;
                if($keyword=="签到"){
                   $keyword=R('Home/Index/png',array($fromUsername));
                   //引入模版
                        $newsTplHead = "<xml>
                                        <ToUserName><![CDATA[%s]]></ToUserName>
                                        <FromUserName><![CDATA[%s]]></FromUserName>
                                        <CreateTime>%s</CreateTime>
                                        <MsgType><![CDATA[news]]></MsgType>
                                        <ArticleCount>1</ArticleCount>
                                        <Articles>";
                        $newsTplBody = "<item>
                                        <Title><![CDATA[%s]]></Title> 
                                        <Description><![CDATA[%s]]></Description>
                                        <PicUrl><![CDATA[%s]]></PicUrl>
                                        <Url><![CDATA[%s]]></Url>
                                        </item>";
                        $newsTplFoot = "</Articles>
                                        <FuncFlag>0</FuncFlag>
                                   </xml>";
         $header = sprintf($newsTplHead, $fromUsername, $toUsername, time());//头
            $title = '这里是智慧源动力第二次全体会议!';
            $desc = '点击阅读全文，扫描二维码，进行签到！';
            $picUrl = $keyword;
            $url = $keyword;
        $body = sprintf($newsTplBody, $title, $desc, $picUrl, $url);
            $FuncFlag = 0;
        $footer = sprintf($newsTplFoot, $FuncFlag);
        return $header.$body.$footer;
        exit;
                }
		    }
	    //翻译
	     //$word = translate($keyword, 'en', 'zh');
	    //$contentStr = $word['trans_result'][0]['dst'];
	   return  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $keyword);
	   
}


}