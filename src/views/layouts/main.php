<?php

use app\assets\AppAsset;
use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $content string
 */

// Register asset bundle
AppAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web'); ?>/favicon.png" type="image/png">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $this->render('_navbar') ?>

<div class="container">
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">
            <span class="label label-primary"><?= Yii::$app->id; ?></span>
        </p>
        <p class="pull-right">
            <span class="label label-default"><?= getenv('HOSTNAME') ?></span>
            <span class="label <?= YII_ENV_PROD ? 'label-success' : 'label-danger' ?>"><?= YII_ENV ?></span>
            <span class="label label-warning <?= YII_DEBUG ? '' : 'hidden' ?>">debug</span>
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
