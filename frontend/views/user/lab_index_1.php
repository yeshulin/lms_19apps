  <?php
    use yii\helpers\url;
    ?>
 <?php
    $this->registerJsFile('@web/mlv/js/amazeui.min.js');    
    $this->registerCssFile('@web/mlv/css/amazeui.min.css');
 ?>
  <style type="text/css">
	  .list-style tbody tr td {
		  height: 30px;
		  text-align: center;
		  padding-bottom: 0;
		  padding-top: 0;
	  }
  </style>
<div class="user-content">
	<div class="user-main-box">
		<a href="#">我的实验室<font>&nbsp;&gt;</font></a>
		<div class="am-tabs" data-am-tabs>			
				<ul class="am-tabs-nav am-nav am-nav-tabs">					
					<div class="l">我的实验室</div>
					<li class="am-active"><a href="#tab1">使用实验室</a></li>
				    <li><a href="#tab2">管理实验室</a></li>
				    <li><a href="#tab3">我的收藏</a></li>
				</ul>					 
		  <div class="am-tabs-bd">
		    <div class="am-tab-panel am-fade am-in am-active" id="tab1">
				<ul class="user-content-course clearfix" id="user-myCause">
					<?php
					foreach($labdescktops["lablist"] as $key=>$val){
						if($val['lab_use']==11){
					?>
					<li>
						<a href="<?=$ygcurl?>" target="_blank"><img src="http://<?=Url::to($val['icon_file'])?>" alt="<?=$val['lab_name']?>"></a>
						<p><i class="icon-eye-open"></i><span><?=$labdescktops["desktoplist"][0]['end_time']?></span></p>
						<h4><a href="<?=$ygcurl?>" target="_blank"><?=$val['lab_name']?></a></h4>
					</li>
						<?php
						}
					}
					?>
					<?php
					foreach($labwangjie as $key=>$val){
							?>
							<li>
								<a href="<?=$val['lab_url']?>" target="_blank"><img src="http://<?=Url::to($val['goods_thumb'])?>" alt="<?=$val['lab_name']?>"></a>
								<p><i class="icon-eye-open"></i><span><?=$val['end_time']?></span></p>
								<h4><a href="<?=$val['lab_url']?>" target="_blank"><?=$val['lab_name']?></a></h4>
							</li>
							<?php
					}
					?>
				</ul>


		    </div>
		    <div class="am-tab-panel am-fade" id="tab2">
				<table width="100%"  class="list-style">
					<tr>
						<td>实验室名称</td><td>申请时间</td><td>开始时间</td><td>有效截止时间</td><td>激活码</td><td>管理</td>
					</tr>
					<?php
					foreach($lablist as $key=>$val){

						?>
						<tr >
							<td><?=$val['lab_name']?></td><td><?=date("Y-m-d H:i:s",$val['created_at'])?></td><td><?=$val['begin_time']?></td><td><?=$val['end_time']?></td><td><?php foreach($val['lab_info'] as $k1=>$v1){ echo $v1[''];}?></td><td><a href="<?=Url::to(["/user/lab/setgrant",'lab_id'=>$val["lab_id"],'lab_name'=>$val['lab_name']])?>">管理用户</a></td>
						</tr>
						<?php
					}
					?>
				</table>
		    </div>
			  <div class="am-tab-panel am-fade" id="tab3">
				  <script id="course-dl-list" type="text/x-jsrender">
					  <li>
						  <a href="#"><img src="{{:img}}" alt=""></a>
						  <h4><a href="#">{{:title}}</a></h4>
						  <p><i class="icon-eye-open"></i><span></span></p>
					  </li>
				  </script>
				  <ul class="user-content-course clearfix" id="user-Mylab">

				  </ul>
				  <div id="user-myCause-collect-page"></div>
			  </div>
		  </div>
		</div>
	</div>
</div>
  <script>
	  var rendDataObj = new renderData();
	  var getDataObj = new getData();

	  function getLabCollect(pagesize,current,target,pageObj)
	  {
		  getDataObj.getMyCollect('lab', pagesize, current, function (result) {
			  var _datas = result.data;
			  var u_data = $.map(_datas.data, function (n) {
				  if (n.items == null) {
					  return {};
				  }
				  return {
					  img: n.items.thumb ? n.items.thumb : urlPre + "/mlv/img/temp/asd8972143912.png",
					  title: n.items.lab_name ? n.items.lab_name : "课程名待定",
//					  doneSum: n.items.doneSum ? n.items.doneSum : "0",
//					  type: '1'
//				countDown : n.course.countDown?n.course.countDown:"017-06-06  00:00:00",
//				compuSum : n.course.compuSum?n.course.compuSum:"0"
				  };
			  });
			  if (u_data.length != 0) {
				  var template = $.templates("#course-dl-list");
				  target.html(template.render(u_data));

				  var _pageObj = $.extend(pageObj,{
					  currentPage:_datas.currentPage,
					  pageSize:_datas.pageSize,
					  total:_datas.total,
					  dataTarget : target
				  });
				  //				console.log(_pageObj);
				  //				console.log(_data);
				  rendDataObj.renderPageModel(_pageObj, target);
			  }
		  });
	  }


	  $(function(){
		  getLabCollect(6, 1, $("#user-Mylab"), {
			  page_target : "#user-myCause-collect-page",
			  page_btFn : "getMycauseCollect"
		  });
	  })
  </script>
