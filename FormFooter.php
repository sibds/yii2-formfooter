<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 13.02.16
 * Time: 17:59
 */

namespace sibds\form;


use yii\bootstrap\Html;
use yii\bootstrap\Widget;

class FormFooter extends Widget
{
    public $model;

    public function run()
    {
        parent::run();

        $content = Html::submitButton(
            $this->model->isNewRecord ? 'Create' : 'Update',
            ['class' => $this->model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        return Html::tag('div', $content, ['class'=>'form-group']);
    }
}