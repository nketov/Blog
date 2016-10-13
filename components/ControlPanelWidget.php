<?php

namespace app\components;

use yii\base\Widget;

class ControlPanelWidget extends Widget
{

    public $panelCount;

    public function run()
    {
        return $this->render('panel');
    }
}