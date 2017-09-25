function doSwf2(){


			var isError=false;
			
			var testTime = setTimeout(function(){
			
				var player = document.getElementById("SobeyPlayer");
				//player.PlayNew("live://sjid:827", 'p2plives');
				
			},15000);
			
			//直播回看
			function getprogramData(id){
				
				//正常播放
				//var data = '{"Name":"让世界听见(重播)","isPlaying":0,"Description":"让世界听见(重播)","Length":"00:10:00","Id":847,"PlayTime":"2016-07-27 10:40:00","starttime":"2016-07-27 10:40:00","endtime":"2016-07-27 10:50:00","time":"0","url":"mr://j:[{\'title\':\'900K\',\'url\':\'http://113.142.30.79/live/7de7dad4be6b47b3a7d2fb24b4bb0915?fmt=h264_500k_flv\'}]","channelid":"7de7dad4be6b47b3a7d2fb24b4bb0915"}';
				//不能播放
				var data = '{"Name":"午夜剧场","isPlaying":0,"Description":"午夜剧场","Length":"02:00:00","Id":839,"PlayTime":"2016-07-26 23:45:00","starttime":"2016-07-26 23:45:00","endtime":"2016-07-27 01:45:00","time":"0","url":"mr://j:[{\'title\':\'900K\',\'url\':\'http://113.142.30.79/live/7de7dad4be6b47b3a7d2fb24b4bb0915?fmt=h264_500k_flv\'}]","channelid":"7de7dad4be6b47b3a7d2fb24b4bb0915"}';
				
				if(isError)
				{
					var data = '{"Name":"中国梦纪录片展播","isPlaying":0,"Description":"中国梦纪录片展播","Length":"00:21:00","Id":872,"PlayTime":"2016-07-28 06:00:00","starttime":"2016-07-28 06:00:00","endtime":"2016-07-28 06:21:00","time":"0","url":"mr://j:[{\'title\':\'900K\',\'url\':\'http://113.142.30.79/live/7de7dad4be6b47b3a7d2fb24b4bb0915?fmt=h264_500k_flv\'}]","channelid":"7de7dad4be6b47b3a7d2fb24b4bb0915"}';

				}
	
				 try{
					 JSON.parse(data)
				 }catch(e)
				 {
					console.log("e: " + e)
				 }
							 
				return ((typeof JSON.parse != 'undefined') ? JSON.parse(data) : JSON.evaluate(data));
				
			}
			
			//直播回看视频流播放状态 回调
			function onPlayerStateChanged(options) {
				isError = true;
				var state = options.state;
				var guid = options.guid;
				//console.log("state="+state)
				var playUrl = "live://sjid:875";
				if (state == 'playbackError') 
				{
					var player = document.getElementById("SobeyPlayer");
					player.PlayNew(playUrl, "p2plives");
				} 
				else if (state == 'completed') 
				{
					var player = document.getElementById("SobeyPlayer");
				} 
				else if (state == 'playing') 
				{
					window['playList'].data("trying", 0);
				}
			}
			
			//获取栏目ID
			function getCatalogId(){
				return 321;
			}
			
			}