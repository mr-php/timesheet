<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

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
    public function rules(): array
    {
        return [
            [['staff', 'projects'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'staff' => Yii::t('app', 'Staff'),
            'projects' => Yii::t('app', 'Projects'),
        ];
    }
}
