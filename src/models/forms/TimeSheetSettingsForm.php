<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class TimeSheetSettingsForm extends Model
{
    /**
     * @var string
     */
    public $timesheetStaff;

    /**
     * @var string
     */
    public $timesheetProjects;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['timesheetStaff', 'timesheetProjects'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'timesheetStaff' => Yii::t('app', 'Staff'),
            'timesheetProjects' => Yii::t('app', 'Projects'),
        ];
    }
}
