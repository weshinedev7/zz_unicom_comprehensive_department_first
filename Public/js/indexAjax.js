jQuery(document).ready(function() {
	$('#loginSuccess').hide();
	$('#loginError').hide();
	var postUrl=appUrl+"/Admin/Index/test";
	//通过前面定义的变量获取地址
	$('#submitButton').click(function(){
	// $.post('d1',{json:数据},{回调函数});
	//post可以传递json数据，如下：
		$.post(postUrl,{'name':$("#name").val(),'password':$("#password").val()},function(data){
			/*data是返回的值*/
			if(data.flag==1){
				//返回的是json数据，详细看php代码
				$('#loginSuccess').fadeIn();
				$('#loginError').fadeOut();
			}else{
				$('#loginError').fadeIn();
				$('#loginSuccess').fadeOut();					   
			}
		});
	});
 });