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
            $this->model->isNewRecord ? self::t('messages', 'Create') : self::t('messages', 'Update'),
            ['class' => $this->model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        return Html::tag('div', $content, ['class'=>'form-group well']);
    }

    public function registerTranslations()
    {
        $i18n = \Yii::$app->i18n;
        $i18n->translations['sibds/form/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/sibds/yii2-formfooter/messages',
            'fileMap' => [
                'sibds/grid/messages' => 'messages.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('sibds/form/' . $category, $message, $params, $language);
    }
}