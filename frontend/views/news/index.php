<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '新闻动态';
$this->registerJsFile('@web/mlv/js/amazeui.min.js');    
$this->registerCssFile('@web/mlv/css/amazeui.min.css');
$this->registerJsFile('@web/mlv/js/get-data.js', ['position'=>\yii\web\View::POS_HEAD]);
$this->registerCssFile('@web/mlv/js/Swiper-3.3.1/dist/css/swiper.min.css');
$this->registerJsFile('@web/mlv/js/Swiper-3.3.1/dist/js/swiper.jquery.min.js');

?>
<style>
	body{
		background: #eee;
	}
	.contact-box .contact-item1{
		width: 580px;
		height: 235px;
		background-color: #ffffff;
		border-bottom: 1px solid #d0d0d0;
		float: left;
		padding: 0 22px;
		margin-bottom: 30px;
	}
	.contact-box .contact-item1 dt{
		    height: 65px;
    padding-top: 20px;
    font-weight: bold;
    font-size: 18px;
    color: #333333;
    border-bottom: 1px solid #eeeeee;
	}
	.contact-box .contact-item1 dt i{
		float: right;
		cursor: pointer;
	}
	.contact-box .contact-item1 dd{
		padding-left: 18px;
		padding-top: 20px;
	}
	.contact-box .contact-item1 dd p{
		font-size: 14px;
		color: #666666;
		margin-bottom: 15px;
	}
	.contact-box .contact-item1 dd p span{
		margin-right: 70px;
	}
	/*分割*/
	.contact-box .contact-item2{
		width: 380px;
		height: 220px;
		background-color: #ffffff;
		border-bottom: 1px solid #d0d0d0;
		float: left;
		padding: 0 22px;
		margin-left: 30px;;
		margin-bottom: 40px;
	}
	.contact-box .contact-item2 dt{
		    height: 54px;
    padding-top: 16px;
    font-weight: bold;
    font-size: 14px;
    color: #666666;
    border-bottom: 1px solid #eeeeee;
    padding-left: 9px;
	}
	.contact-box .contact-item2 dt i{
		float: right;
		cursor: pointer;
	}
	.contact-box .contact-item2 dd{
		padding-left: 9px;
		padding-top: 10px;
		padding-right: 12px;
	}
	.contact-box .contact-item2 dd p{
		font-size: 14px;
		color: #999999;		
		line-height: 24px;
	}	
	/*分割*/
	.map-icon1{
		display: block;
		width: 28px;
		height: 28px;
		background: url(<?=Url::to('@web/mlv/img/icon/map-icon.png', true)?>) no-repeat 0 0;
	}
	.map-icon2{
		display: block;
		width: 28px;
		height: 28px;
		background: url(<?=Url::to('@web/mlv/img/icon/map-icon.png', true)?>) no-repeat -28px 0;
	}
	.map-icon3{
		display: block;
		width: 28px;
		height: 28px;
		background: url(<?=Url::to('@web/mlv/img/icon/map-icon.png', true)?>) no-repeat -56px 0;
	}
	.map-icon3:hover{
		background-position: 0 0;
	}
	
	.map-mask{
		width:100%;
		height:100%;
		position:fixed;
		background:rgba(0,0,0,0.7);
		top:0;
		left:0;
		display:none;
		z-index: 1;
	}
	.map-content{
		width:1220px;
		height:420px;
		margin:0 auto;
		margin-top:200px;
		background:#fff;
	}
	.contact-box .contact-item1:nth-child(even){float:right;}

</style>
<div class="bread-crumbs">
            <div>
                <a href="/">首页</a>
                <span>&nbsp;&gt;&nbsp;</span>
                <span><?php echo $this->title;?></span>
            </div>
</div>
<script tag="news_list" id="news_list" type="text/x-jsrender" class="render-model">
	<dl class="contact-item1">
		<dt>
			<i class="map-icon3 map-info"  data-map="{{:city}}"></i>
			<span>【{{:title}}】</span>
		</dt>
		<dd>
			<p>地址：{{:address}}</p>
			<p>邮编：{{:zipcode}}</p>
			<p>电话：{{:phone}}</p>
			<p>传真：{{:fax}}</p>
		</dd>
	</dl>
</script>

<div>
	<div class="inner">
		<div class="clearfix">
			<div class="article-list-header mbl row">
				<!-- 轮播图 -->
				<div class="aricle-carousel col-md-8">
					<!-- Swiper -->
					<div class="swiper-container">
						<div class="swiper-wrapper">

						</div>
						<!-- Add Pagination -->
						<div class="swiper-pagination swiper-pagination-white"></div>
						<!-- Add Arrows -->
						<div class="swiper-button-next swiper-button-white"></div>
						<div class="swiper-button-prev swiper-button-white"></div>
					</div>
				</div>
				<div class="col-md-4 hidden-sm hidden-xs">
					<a href="/article/910" class="topic-post-small">
						<img class="img-responsive" src="http://www.qiqiuyu.com//assets/v2/img/article/article_banner_7.jpg?version=8.1.7" alt="一场成功的砍价活动是如何炼成的？！">
						<div class="content">
							<p>微营销动态</p>
							<h4 class="title">一场成功的砍价活动是如何炼成的？！</h4>
						</div>
						<div class="image-overlay"></div>
					</a>
					<a href="/article/911" class="topic-post-small" style="margin-top:12px;">
						<img class="img-responsive" src="http://www.qiqiuyu.com//assets/v2/img/article/article_banner_6.jpg?version=8.1.7" alt="简单有用的“砍价”功能上线啦！">
						<div class="content">
							<p>微营销动态</p>
							<h4 class="title">简单有用的“砍价”功能上线啦！</h4>
						</div>
						<div class="image-overlay"></div>
					</a>
				</div>
			</div>
			<div class="article-list-body row">
				<!-- list主内容-->
				<div class="col-md-8 article-list-main">

					<section class="es-section">
						<div class="es-tabs article-list-header">
							<div class="tab-header">
								<script id="catlog-list-template" type="text/x-jsrender">

									<li class=""><a href="{{:href}}">{{:catname}}</a></li>
								</script>
								<ul class="clearfix" id="catlog-list">
									<!--分类模板列表-->
								</ul>
							</div>
						</div>
						<script id="article-list-template" type="text/x-jsrender">
							<article class="article-item">
								<div class="article-metas clearfix">
								<div class="metas-body">

										<h2 class="title">
											<a class="link-dark" href="{{:href}}">{{:title}}</a>
										</h2>
									</div>


								</div>
								<div class="content">
									{{:description}}
								</div>
								<div class="pull-right">
											发布时间：{{:created_at}}
									</div>
							</article>
						</script>
						<div class="article-list" id="article-list">
							<!--文章模板列表-->
						</div>


					</section>
				</div>

<!--				<aside class="col-md-4 article-sidebar">-->
<!-- 热门资讯 -->
<!--					<div class="panel panel-default hot-article">-->
<!--						<div class="panel-heading">-->
<!--							<h3 class="panel-title"><i class="es-icon es-icon-whatshot"></i>热门资讯</h3>-->
<!--						</div>-->
<!--						<div class="panel-body">-->
<!--							<div class="media media-number">-->
<!--								<div class="media-left">-->
<!--									<span class="num">1</span>-->
<!--								</div>-->
<!--								<div class="media-body">-->
<!--									<a class="link-dark" href="/article/500" title="2015地平线报告：K12教育六大趋势">2015地平线报告：K12教育六大趋势...</a>-->
<!--								</div>-->
<!--							</div>-->
<!---->
<!--						</div>-->
<!--					</div>-->
<!---->
<!---->
		<!-- 热门标签 -->
<!--					<div class="panel panel-default hot-tag">-->
<!--						<div class="panel-heading">-->
<!--							<h3 class="panel-title"><i class="es-icon es-icon-loyalty"></i>热门标签</h3>-->
<!--						</div>-->
<!--						<div class="panel-body">-->
<!--							<a class="btn-tag" href="/article/tag/%E9%A2%98%E5%BA%93"> 题库</a>-->
<!---->
<!--						</div>-->
<!--					</div>-->
<!---->
					<!-- 热门评论 -->
<!--					<div class="panel panel-default hot-comments">-->
<!--						<div class="panel-heading">-->
<!--							<h3 class="panel-title">-->
<!--								<i class="es-icon es-icon-textsms"></i>热门评论-->
<!--							</h3>-->
<!--						</div>-->
<!--						<div class="panel-body">-->
<!---->
<!--							<div class="media media-hot-comment">-->
<!--								<div class="media-body">-->
<!--									<div class="pull-left">-->
<!--										<a class=" js-user-card" href="/user/35494" data-card-url="/user/35494/card/show" data-user-id="35494">-->
<!--											<img class="avatar-sm" src="/files/default/2015/05-12/171002aabdd8876310.png">-->
<!---->
<!--										</a>-->
<!---->
<!--									</div>-->
<!--									<div class="comments-info">-->
<!--										<a class="link-dark " href="/user/35494">泽课堂</a>-->
<!---->
<!--										<span class="mhs">评论于</span>-->
<!--										<a class="link-dark" href="/article/785">常用的邮件服务器配置示例</a>-->
<!--									</div>-->
<!--									<div class="comments-content">-->
<!--										smtp验证始终失败，我在本地用foxmail就可以发送成功，阿里企业邮箱，请问是怎么回事哦？-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->
<!---->
<!---->
<!--						</div>-->
<!--					</div>-->
<!--					-->
<!---->
<!--				</aside>-->
			</div>

		</div>
		
	</div>
</div>

<script type="text/javascript">
var getDataObj = new getData();

function doNews(target){
	getDataObj.getContentList(10,1,function(result){
		var u_data = $.map(result.data.data,function(n){
			return {
				title:n.title,
				description:n.description,
				href: '<?= Url::to(['/site/news/view', 'id'=>''])?>'+n.id,
				created_at: formatTime(n.created_at)
			};
		});
		var template = $.templates("#article-list-template");
		var htmlOutput = template.render(u_data);		
		target.html(htmlOutput);			
	});
}
function doCatlog(target){
	getDataObj.getCatlogList(function(result){
		var u_data = $.map(result.data.data,function(n){
			return {
				catid:n.catid,
				catname:n.catname,
				href: '<?= Url::to(['/site/news/list', 'catid'=>''])?>'+n.catid
			};
		});
		var template = $.templates("#catlog-list-template");
		var htmlOutput = template.render(u_data);
		target.html(htmlOutput);
	});
}


	$(function(){
		doNews($("#article-list"));
		doCatlog($("#catlog-list"));
		$.get('/api/advertisement/get?id=26', function (result) {
			//console.log(result);
			if (result.data !== undefined) {
				$.each(result.data.content, function (k, v) {
					if (v.type == "1") {
						$(".swiper-wrapper").append('<div class="swiper-slide" style="background-image: url(' + urlPre + v.content + ');"></div>');
					}
				})

				var swiper = new Swiper('.swiper-container', {
					pagination: '.swiper-pagination',
					paginationClickable: true,
					nextButton: '.swiper-button-next',
					prevButton: '.swiper-button-prev',
					spaceBetween: 30,
					effect: 'fade',
					autoplay : 5000,
					loop : true
				});

			}
		})

	})
</script>
