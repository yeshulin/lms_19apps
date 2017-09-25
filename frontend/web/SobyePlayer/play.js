  // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection.
 function doSwf1(){


  
            var swfVersionStr = "11.4.0";
            // To use express install, set to playerProductInstall.swf, otherwise the empty string. 
            var xiSwfUrlStr = "/playerProductInstall.swf";
            var flashvars = {};
       		var date="731E4wFefooaffEfhebfe721203a1c";//new Date().getTime();
       		flashvars.siteID=594;
			
			//直播类型 8-视频直播
			flashvars.mediaType="8";
			flashvars.exparam="%3FsiteId%3D882%26channelId%3D204fe35f29f54342bfaf0178792ba087";
			flashvars.staticParams = "{'Dimension':'PC'}";
			flashvars.profile = "/ProfileTest.json";
			
			//直播地址 为json字符串，mr://j用于判断该地址是否直接播放，请改动rul的值
			
			//flv   视频
			//flashvars.url='mr://j:{"playerUrl":[{"title":"900K","url":"http://livepgc.sobeycache.com/pgc/afda327b3a99488a425f060e95ef600c.flv?auth_key=1501742565-0-0-8a4cdf9a4697d7658b071228e331aab9"}],"status":1,"catalogId":"7de7dad4be6b47b3a7d2fb24b4bb0915"}';
			
			//rtmp  视频
			flashvars.url='mr://j:{"playerUrl":[{"title":"900K","url":"rtmp://livepgc.sobeycache.com/pgc/64215ad0707861d82add466935c84b9e?auth_key=1501655488-0-0-68417fb79191046c25789ec3eaca4f5b"}],"status":1,"catalogId":"7de7dad4be6b47b3a7d2fb24b4bb0915"}';	
			
			//m3u8  视频
			//flashvars.url='mr://j:{"playerUrl":[{"title":"900K","url":"http://livepgc.sobeycache.com/pgc/4233debb406e8622dece8ae2fb845ce9.m3u8?auth_key=1501726678-0-0-a1f04f8227713f55e1026a3e06fa4315"}],"status":1,"catalogId":"7de7dad4be6b47b3a7d2fb24b4bb0915"}';
			
		
			
            var params = {};
            params.quality = "high";
            params.bgcolor = "#ffffff";
            params.allowscriptaccess = "always";
            params.allowfullscreen = "true";
            params.wmode="transparent";
            var attributes = {};
            attributes.id = "SobeyPlayer";
            attributes.name = "SobeyPlayer";
            attributes.align = "middle";
            swfobject.embedSWF(
                "/SobeyPlayer.swf"+"?ver="+date, "flashContent", 
                "100%", "100%",
              //	"475", "310", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes);
            
            swfobject.createCSS("#flashContent", "display:block;text-align:left;");
			
			//这是用在直播频道上取广告用的
			function getadData()
			{
				return;
				var ads="[";
				ads+="{'src':'http://static.vms.sobeycache.com/hzwldst/media/player/dt.jpg','id':'1238','stretch':'1','duration':'1000000000','x':'0','y':'0','opacity':'1','playtime':'0','adtype':'1','interval':'10','cushion':'0','height':'1','width':'1','CanClose':'0','href':'http://www.hzrtv.cn/'}";
				ads+="]";
				return ads;
			}
   		//	document.getElementById("flashContent").disabled=false;
   			}