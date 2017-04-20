<?php

/**
 * @var View $this
 * @var array $toggl
 * @var array $times
 * @var array $totals
 */

use yii\web\View;

$this->title = Yii::$app->name;
?>

<div class="site-index">
    <?= $this->render('_current', ['toggl' => $toggl]) ?>
    <ul id="main-tab" class="nav nav-pills" role="tablist">
        <li class="active"><a href="#summary">Summary</a></li>
        <li><a href="#daily">Daily</a></li>
        <li><a href="#invoices">Invoices</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="summary">
            <?= $this->render('_summary', ['totals' => $totals]) ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="daily">
            <?= $this->render('_daily', ['totals' => $totals]) ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="invoices">
            <?= $this->render('_invoices', ['times' => $times]) ?>
        </div>
    </div>
</div>

<?php ob_start(); // output buffer the javascript to register later ?>
<script>
    $('#main-tab').find('a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#daily-tab').find('a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#invoices-tab').find('a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
</script>
<?php $this->registerJs(str_replace(['<script>', '</script>'], '', ob_get_clean()), View::POS_END); ?>
