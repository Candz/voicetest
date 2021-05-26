<?php
// 声明页面header
header("Content-type:charset=utf-8");

// 声明APPID、APPSECRET
$appid = "wxe2092ab5c9db86d1";
$appsecret = "2987a43822694a1788ea3204dae7aeda";

// 获取access_token和jsapi_ticket
function getToken(){
   $file = file_get_contents("access_token.json",true);//读取access_token.json里面的数据
   $result = json_decode($file,true);

//判断access_token是否在有效期内，如果在有效期则获取缓存的access_token
//如果过期了则请求接口生成新的access_token并且缓存access_token.json
if (time() > $result['expires']){
       $data = array();
       $data['access_token'] = getNewToken();
       $data['expires'] = time()+7000;
       $jsonStr =  json_encode($data);
       $fp = fopen("access_token.json", "w");
       fwrite($fp, $jsonStr);
       fclose($fp);
       return $data['access_token'];
   }else{
       return $result['access_token'];
   }
}

//获取新的access_token
function getNewToken($appid,$appsecret){
   global $appid;
   global $appsecret;
   $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret."";
   $access_token_Arr =  file_get_contents($url);
   $token_jsonarr = json_decode($access_token_Arr, true);
   return $token_jsonarr["access_token"];
}

$access_token = getToken();

//缓存jsapi_ticket
function getTicket(){
   $file = file_get_contents("jsapi_ticket.json",true);//读取jsapi_ticket.json里面的数据
   $result = json_decode($file,true);

//判断jsapi_ticket是否在有效期内，如果在有效期则获取缓存的jsapi_ticket
//如果过期了则请求接口生成新的jsapi_ticket并且缓存jsapi_ticket.json
if (time() > $result['expires']){
       $data = array();
       $data['jsapi_ticket'] = getNewTicket();
       $data['expires'] = time()+7000;
       $jsonStr =  json_encode($data);
       $fp = fopen("jsapi_ticket.json", "w");
       fwrite($fp, $jsonStr);
       fclose($fp);
       return $data['jsapi_ticket'];
   }else{
       return $result['jsapi_ticket'];
   }
}

//获取新的access_token
function getNewTicket($appid,$appsecret){
   global $appid;
   global $appsecret;
   $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".getToken()."";
   $jsapi_ticket_Arr =  file_get_contents($url);
   $ticket_jsonarr = json_decode($jsapi_ticket_Arr, true);
   return $ticket_jsonarr["ticket"];
}

$jsapiTicket = getTicket();

// 动态获取URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// 生成时间戳
$timestamp = time();

// 生成nonceStr
$createNonceStr = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
str_shuffle($createNonceStr);
$nonceStr = substr(str_shuffle($createNonceStr),0,16);

// 按照 key 值 ASCII 码升序排序
$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

// 按顺序排列按sha1加密生成字符串
$signature = sha1($string);

?>




<!DOCTYPE html>
<html>
<head lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="apple-touch-fullscreen" content="YES">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,target-densitydpi=device-dpi">
<meta name="format-detection" content="telephone=no">
<title>声鉴卡</title>
<link rel="stylesheet" href="assets/lz-icon.css">
<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">
<link href="assets/index.css" rel="stylesheet">
</head>
<body>
<div class="container soundWrap" style="display:block">
  <div class="page1" style="display:block">
    <input class="name-input" maxlength="10" type="text" placeholder="给自己起个好听的名字">
    <div class="sel-boy sex"><img src="assets/boy.png" alt="0"></div>
    <div class="sel-girl sex active"><img src="assets/girl.png" alt="1"></div>
    <div class="discrBtn"></div>
    <img class="lizhi-icon" src="assets/logo.png" alt="出品人"> </div>
  <div class="page2" style="display:none"> <img class="p2_1 pa" src="assets/text.png" alt=""> <span class="progress_tit">Time</span>
    <div class="progress-wrap">
      <div class="progressing" style="width:0"></div>
    </div>
    <div class="secondText">0s</div>
    <img class="p2_2 pa" src="assets/luyinji.png" alt=""> <img class="p2_3 pa" src="assets/songkai.png" alt=""> <img src="assets/luyin.png" alt="正在录音" class="recording">
    <div class="record_btn pa" ontouchstart="jishu()" ontouchend="ting()"> <img src="assets/anzhu.png" alt=""> </div>
    <img class="p2_4 pa" src="assets/wenan.png" alt="">
    <div class="alert1"> <img class="alert_bg alert1_bg" src="assets/fenxi.png" alt=""> <img class="success_bg" src="assets/chenggong.png" alt=""> <img class="reset_bg" src="assets/huannicheng.png" alt="">
      <div class="loading"></div>
      <div class="look_result" onClick="_hmt.push([&quot;_trackEvent&quot;,&quot;查看结果&quot;,&quot;点击&quot;])"></div>
      <div class="reset1" onClick="_hmt.push([&quot;_trackEvent&quot;,&quot;重新录制&quot;,&quot;点击&quot;])"></div>
      <div class="cancal-btn"></div>
    </div>
    <div class="alert2" style="display:none"> <img class="alert_bg" src="assets/jibao.png" alt="">
      <div class="reset"></div>
      <div class="cancal-btn"></div>
    </div>
  </div>
</div>
<div class="page3" id="page3" style="display:none">
  <div class="tip">长按图片保存，分享到朋友圈</div>
  <img class="shareImg" id="shareImg" style="display:block" src="" alt=""> </div>
  <script type="text/javascript" src="assets/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="assets/index.js"></script>
<div class="msgbox" style="position: fixed; left: 0px; top: 40%; height: 10%; width: 100%; text-align: center; color: white; z-index: 99999999; line-height: 10%; display: none;"><span style="padding: 2.5% 8%; border-radius: 6px; font-family: 微软雅黑; font-size: 24px; background-color: rgba(0, 0, 0, 0.498039);">请输入名字</span></div>


</body>
<!-- </html> -->

<script src="http://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>

<script>
    var url = location.href;
    wx.config({
       debug: false, // 正式上线后改成false不在弹出调试信息
       appId: '<?php echo $appid;?>',
       timestamp: '<?php echo $timestamp;?>',
       nonceStr: '<?php echo $nonceStr;?>',
       signature: '<?php echo $signature;?>',
       jsApiList: [
         // 所有要调用的 API 都要加到这个列表中
         'updateAppMessageShareData', //分享到朋友圈
         'updateTimelineShareData',//分享给朋友
       ]
     });

    wx.ready(function () {
        var shareData = {
            title: '在线小霸王游戏机，1秒找回童年',
            desc: '超级玛丽，魂斗罗，淘金者，冒险岛！80,90一代人的回忆',
            link: url,
            imgUrl: 'https://v19.phph5.cn/xbw/logo1.png'
        };

        wx.updateAppMessageShareData(shareData);//分享给好友
        wx.updateTimelineShareData(shareData);//分享到朋友圈
        wx.onMenuShareQQ(shareData);//分享给手机QQ
        wx.onMenuShareWeibo(shareData);//分享腾讯微博
        wx.onMenuShareQZone(shareData);//分享到QQ空间



    });
    wx.error(function (res) {
        alert(res.errMsg);//错误提示

    });
</script>

</html>