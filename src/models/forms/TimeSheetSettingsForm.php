<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * Class TimeSheetSettingsForm
 * @package app\models\forms
 */
class TimeSheetSettingsForm extends Model
{
    /**
     * @var string
     */
    public $staff;

    /**
     * @var string
     */
    public $projects;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff', 'projects'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staff' => Yii::t('app', 'Staff'),
            'projects' => Yii::t('app', 'Projects'),
        ];
    }
}
