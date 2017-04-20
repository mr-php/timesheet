<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class TogglSettingsForm extends Model
{

    /**
     * @var string
     */
    public $startDate;

    /**
     * @var string
     */
    public $endDate;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['startDate', 'endDate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'startDate' => Yii::t('app', 'Start Date'),
            'endDate' => Yii::t('app', 'End Date'),
        ];
    }
}
