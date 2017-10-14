<?php
    use yii\helpers\Url;
    ?>
    <?php
    $this->registerJsFile('@web/mlv/js/jquery-1.9.1.min.js',['position'=>\yii\web\View::POS_HEAD]);
    $this->registerJsFile('@web/mlv/js/sobeyPlug-in.js',['position'=>\yii\web\View::POS_HEAD]);
    $this->registerJsFile('@web/mlv/js/jsrender.min.js',['position'=>\yii\web\View::POS_HEAD]);
    $this->registerJsFile('@web/mlv/js/member-common.js',['position'=>\yii\web\View::POS_HEAD]);
    $this->registerJsFile('@web/mlv/js/amazeui.min.js');
    $this->registerJsFile('@web/mlv/js/header.js');
//    $this->registerJsFile('@web/mlv/js/header.js');
	

	  
	$this->registerCssFile('@web/mlv/js/cropper-master/assets/css/bootstrap.min.css');
    $this->registerCssFile('@web/mlv/css/amazeui.min.css');
    $this->registerCssFile('@web/mlv/css/head.css');
    $this->registerCssFile('@web/mlv/css/css.css');
    $this->registerCssFile('@web/mlv/css/font-awesome.min.css');

    ?>
    <script>
        var userInfo = <?php echo Yii::$app->user->identity?json_encode(\common\helpers\Stringnew::arrayFilterMember(Yii::$app->user->identity->toArray())):"null"?>;
        var urlPre = "<?=Url::to('@web/', true)?>";
        function serach(o){
            var  _value = $(o).siblings("input").val();
            if (_value != "")
            {
                window.location.href = "<?= Url::to(['/site/course/list', 'courseName'=>''])?>"+_value;
            }
        }
    </script>
    <div class="clearfix" id="header-box" style="position: relative;">
        <div class="header-logo">
            <a href="/">
                <img src="<?=Url::to('@web/mlv/img/head/logo.png', true)?>" alt=""/>
            </a>
        </div>
        <ul class="header-nav" id="header-nav">

        </ul>
        <div id="header-right">

        </div>

        <script id="headerNav" type="text/x-jsrender">
            <li>
                <a href="{{:url}}">
                    {{:name}}
                </a>
            </li>
            </script>

        <script id="headerTmpl" type="text/x-jsrender">
        <div class="header-user">
            <div class="header-user-bt">
                <a style="display: none;" href="#">高校圈子</a>
                <a style="display: none;" href="#">关于我们</a>
                {{if logined}}
                    <a href="<?= Url::to(['/user'])?>">
                    {{if nickname}}
                        {{:nickname}}
                    {{else}}
                        {{:name}}
                    {{/if}}
                     </a>
                     <a href="javascript:void(0);" id="user-logout" style="margin-left: 10px;">退出</a>
                    {{else}}
                     <a href="<?= Url::to(['/auth/login']) ?>">登录</a>
                    <a href="<?= Url::to(['/auth/reg']) ?>" style="margin-left: 10px;">注册</a>
                {{/if}}
               
            </div>
            <div class="header-search">
                <input type="text" placeholder="输入搜索"/>
                    <span onclick="serach(this)">
                        <i class="icon-search"></i>
                    </span>
                <div style="display: none;">
                    <a href="javascript:void(0);">花字制作</a>
                    <a href="javascript:void(0);">大数据</a>
                </div>
            </div>
        </div>
    </script>
    </div>
<script type="text/javascript">


    $(function(){
        var getDataObj = new getData();
        var order = {"order":"order"};


        getDataObj.getNavList("order",function(result){
            var data = $.map(result.data.data,function(n){
                return n;
            });
            var template = $.templates("#headerNav");
            var htmlOutput = template.render(data);
            $("#header-nav").html(htmlOutput);
        });
    })
</script>
