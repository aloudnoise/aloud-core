<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="en" class="h-100">
    <head>
        <meta charset="UTF-8"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico?v=3" type="image/x-icon" />
        <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <?php

        (Yii::$app->assetManager->getBundle('jquery'))::register($this);
        (Yii::$app->assetManager->getBundle('tools'))::registerJgrowl($this);
        (Yii::$app->assetManager->getBundle('tools'))::registerTool($this, 'waves');
        (Yii::$app->assetManager->getBundle('jstrans'))::register($this);
        (Yii::$app->assetManager->getBundle('bootstrap'))::register($this);
        (Yii::$app->assetManager->getBundle('base'))::register($this);
        (Yii::$app->assetManager->getBundle('theme'))::register($this);
        (Yii::$app->assetManager->getBundle('font_awesome'))::register($this);

        $this->registerJsFile("https://www.youtube.com/player_api", [
            'position' => \yii\web\View::POS_END
        ], "youtube_js");

        $this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.2/MathJax.js?config=TeX-MML-AM_CHTML", [
            'position' => \yii\web\View::POS_HEAD
        ], "mathjax_lib");


        $socket_url = Yii::$app->params['socket_server'] ?: "https://".$_SERVER['HTTP_HOST'].":8081";
        $api_url = Yii::$app->params['api_url'];
        $files_host = Yii::$app->params['files_host'];

        $this->registerJs("// Some php vars
                BACKBONE_ASSETS = '".Yii::$app->assetManager->getBundle("backbone")->baseUrl."';
                BASE_ASSETS = '".Yii::$app->assetManager->getBundle("base")->baseUrl."';
                DEBUG = ".intval(YII_DEBUG).";
                URL_ROOT = '';
                SOCKET_URL = '$socket_url';
                API_URL = '$api_url';
                FILES_HOST = '$files_host';
                TRACKING_CODE = false;
            ", View::POS_HEAD, 'constants');

        $this->registerJsFile("$socket_url/socket.io/socket.io.js", [

            ], 'sockets');

        $this->head()

        ?>
    </head>
    <body data-spy="scroll" class="h-100 <?=$this->context->layout?>">
    <div class="main-el h-100">
        <?php
            $this->beginBody();
            $bgs = [
                'bg-primary',
                'bg-info',
                'bg-warning',
                'bg-danger',
                'bg-success'
            ];

        ?>
        <div id="preloader" class="">
            <div class="bg <?=$bgs[mt_rand(0,4)]?>"></div>
            <div class="sk-spinner sk-spinner-wave" id="status">
                <div class="sk-rect1"></div>
                <div class="sk-rect2"></div>
                <div class="sk-rect3"></div>
                <div class="sk-rect4"></div>
                <div class="sk-rect5"></div>
            </div>
        </div><!-- End Preload -->
        <?php
        $headers = [
            'auth' => 'auth'
        ]
        ?>
        <div class='wrapper h-100'>
            <div class="inner-el h-100 <?=(\Yii::$app->user->isGuest ? '' : 'logged')?>">
                <div class="body clearfix h-100">
                    <div class="controller-content h-100">
                        <?php
                        echo $content;
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <?=\app\widgets\EMessages\EMessages::widget();?>

        <?php if ($this->context->layout != "@app/views/layouts/print") { ?>
            <?= $this->render("@app/views/layouts/main_footer.twig"); ?>
        <?php } ?>
    </div>

    <script type="text/template" id="controller_modal_template">
        <div class="modal <%=data.controller%> <%=options.no_fade ? "" : "fade"%>" id="controller_modal" data-backdrop="<%=data.modalBackdrop ? data.modalBackdrop : 'static'%>" tabindex="-1" role="dialog" aria-labelledby="controller_modal" aria-hidden="true">
            <div class="modal-dialog <%= data.size ? "modal-" + data.size : "" %> <%= data.classes ? data.classes : "" %>">
                <div class="modal-content <%= data.content_classes ? data.content_classes : "" %>">
                    <% if (!data.noHeader) { %>
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><%= data.pageTitle %></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <% } %>
                    <div class="modal-body">
                        <%= html %>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="controller_template">
        <div class='controller-content'>
            <%= html %>
        </div>
    </script>



    <div class="remove_after_load">
        <script language="javascript">
            $(function() {

                <?php
                $p = Yii::$app->request->get();
                if (isset($p['z'])) {
                    $murl = $p['z'];
                }
                unset($p['z']);

                $baseUrl = Url::to(array_merge(['/'.$this->context->route], $p));

                ?>

                Yii.app = _.extend(new BaseApplication({
                    el : $(".main-el"),
                    innerEl : $(".inner-el"),
                    controllerEl : ".controller-content"
                }), Yii.app);

                // Init current controller
                Yii.app.user = <?=json_encode(Yii::$app->user->identity ? Yii::$app->user->identity->backboneArray() : ['isGuest' => true])?>;

                <?php $model = Yii::$app->response->getModelData(); ?>

                Yii.app.renderController({
                        model: <?=json_encode($model)?>,
                    },
                    "normal",
                    {
                        loaded : true,
                        href : '<?=$baseUrl?>',
                        baseUrl : '<?=$baseUrl?>',
                        noState : <?=$model['isModal'] ? "true" : "false"?>,
                        transaction : true
                    }
                );

                Yii.app.render();
            });
        </script>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>