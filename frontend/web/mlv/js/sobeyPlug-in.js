function sobeyAlert(str,callback){
	var _htmlObj = "";	
	_htmlObj += '<div class="sobey-alert">';
	_htmlObj += '<div class="sobey-alert-wrap">';
	_htmlObj += '<div class="sobey-alert-box">';	
	_htmlObj += '<div>';
	_htmlObj += '<i></i>';
	_htmlObj += '<h2>'+ str +'</h2>';
	_htmlObj += '<p></p>';
	_htmlObj += '<button>确定</button>';
	_htmlObj += '</div></div></div></div>';	
	$("body").append(_htmlObj);
	$(".sobey-alert button").one("click",function(){
		close_sobeyAlert();
		if(!!callback){
			callback();	
		}
		
	});
}
function close_sobeyAlert(){
	$(".sobey-alert").remove();
}
function formatTime(date) {
  var myDate = new Date(date*1000);
  var year = myDate.getFullYear()
  var month = myDate.getMonth() + 1
  var day = myDate.getDate()

  var hour = myDate.getHours()
  var minute = myDate.getMinutes()
  var second = myDate.getSeconds()


  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}
function formatNumber(n) {
  n = n.toString()
  return n[1] ? n : '0' + n
}