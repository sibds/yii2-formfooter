<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 13.02.16
 * Time: 17:59
 */

namespace sibds\form;


use sibds\components\ActiveRecord;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;

class FormFooter extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    public function init()
    {
        $this->registerTranslations();
    }

    public function run()
    {
        parent::run();

        $content = Html::submitButton(
            $this->model->isNewRecord ? self::t('messages', 'Create') : self::t('messages', 'Update'),
            ['class' => $this->model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        $content = Html::tag('div', $content, ['class'=>'col-sm-6']).
            Html::tag('div', $this->getInfoRecord(), ['class'=>'col-sm-6']);

        return Html::tag('div', $content, ['class'=>'form-group well row']);
    }

    private function getInfoRecord(){
        $create = '';
        $update = '';

        if($this->model->hasAttribute($this->model->createdAtAttribute))
            $create = self::t('messages', 'Created').': '.\Yii::$app->formatter->asDatetime($this->model->{$this->model->createdAtAttribute});

        if($this->model->hasAttribute($this->model->updatedAtAttribute))
            $update = self::t('messages', 'Updated').': '.\Yii::$app->formatter->asDatetime($this->model->{$this->model->updatedAtAttribute});

        return strtr('{create}<br/>{update}', ['{create}'=>$create, '{update}'=>$update]);
    }

    public function registerTranslations()
    {
        $i18n = \Yii::$app->i18n;
        $i18n->translations['sibds/form/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/sibds/yii2-formfooter/messages',
            'fileMap' => [
                'sibds/form/messages' => 'messages.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('sibds/form/' . $category, $message, $params, $language);
    }
}