<?php

/**
 * @var View $this
 * @var array $toggl
 * @var array $times
 * @var array $totals
 */

use yii\helpers\VarDumper;
use yii\web\View;

$this->title = Yii::$app->name;
?>

<div class="site-dump">
    <ul id="dump-tab" class="nav nav-pills" role="tablist">
        <li class="active"><a href="#toggl">$toggl</a></li>
        <li><a href="#times">$times</a></li>
        <li><a href="#totals">$totals</a></li>
        <li><a href="#staff">$staff</a></li>
        <li><a href="#projects">$projects</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="toggl">
            <h3><code>$toggl = Yii::$app->cache->get('toggl');</code></h3>
            <pre><?= VarDumper::export($toggl) ?></pre>
        </div>
        <div role="tabpanel" class="tab-pane" id="times">
            <h3><code>$times = Yii::$app->timeSheet->getTimes($toggl);</code></h3>
            <pre><?= VarDumper::export($times) ?></pre>
        </div>
        <div role="tabpanel" class="tab-pane" id="totals">
            <h3><code>$totals = Yii::$app->timeSheet->getTotals($times);</code></h3>
            <pre><?= VarDumper::export($totals) ?></pre>
        </div>
        <div role="tabpanel" class="tab-pane" id="staff">
            <h3><code>$staff = Yii::$app->timeSheet->staff;</code></h3>
            <pre><?= VarDumper::export(Yii::$app->timeSheet->staff) ?></pre>
        </div>
        <div role="tabpanel" class="tab-pane" id="projects">
            <h3><code>$projects = Yii::$app->timeSheet->projects;</code></h3>
            <pre><?= VarDumper::export(Yii::$app->timeSheet->projects) ?></pre>
        </div>
    </div>
</div>

<?php ob_start(); // output buffer the javascript to register later ?>
<script>
    $('#dump-tab').find('a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
</script>
<?php $this->registerJs(str_replace(['<script>', '</script>'], '', ob_get_clean()), View::POS_END); ?>
