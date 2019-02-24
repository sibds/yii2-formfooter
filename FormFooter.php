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
use yii2mod\alert\AlertAsset;

class FormFooter extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;
    
    public $removed = false;

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

        $jsCreateClose = <<<JS
            var action = $(this).parents('form').attr('action');
            var submitButton =  $(this).parent().find('button[type=submit]');
            $(this).parents('form').attr('action', action + '&close=true');
            submitButton.click();
            return false;
JS;

        $content .= ' ' . Html::a($this->model->isNewRecord ?
                self::t('messages', 'Create and close') :
                self::t('messages', 'Update and close'),
                '#', ['class' => 'btn btn-default btn-sm', 'onclick'=>$jsCreateClose]);

        $returnUrl = null;
        
        if(\Yii::$app->controller->defaultAction===null){
            $returnUrl = \Yii::$app->user->returnUrl;
        }else{
            $returnUrl = [\Yii::$app->controller->defaultAction];
        }

        $content .= ' ' . Html::a(self::t('messages', 'Close'), $returnUrl,
                ['class' => 'btn btn-default btn-sm']);
        
        if($this->removed&&!$this->model->isNewRecord){
            AlertAsset::register($this->view);
            $this->view->registerJs("            
            yii.confirm = function (message, okCallback, cancelCallback) {
                swal({
                    title: message,
                    type: 'warning',
                    showCancelButton: true,
                    closeOnConfirm: true,
                    allowOutsideClick: true,
                    cancelButtonText: 'Отмена',
                    confirmButtonText: 'Да',
                    confirmButtonColor: '#c9302c'
                }, okCallback);
            };
            ");
            $content .= ' ' . Html::a(self::t('messages', 'Delete'), ['delete', 'id' => $this->model->id], [
                    'class' => 'btn btn-danger btn-sm pull-right',
                    'data' => [
                        'confirm' => self::t('messages', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                        'pjax' => '0',
                    ],
                ]);
        }

        $content = Html::tag('div', $content, ['class'=>'col-sm-8']).
            Html::tag('div', $this->getInfoRecord(), ['class'=>'col-sm-4 text-right']);

        return Html::tag('div', $content, ['class'=>'form-group well row']);
    }

    private function getInfoRecord(){
        $created = '';
        $updated = '';

        if($this->model->hasAttribute($this->model->createdAtAttribute))
            $created = self::t('messages', 'Created').': '.
                \Yii::$app->formatter->asDatetime(
                    $this->model->{$this->model->createdAtAttribute}, 'short');

        if($this->model->hasAttribute($this->model->updatedAtAttribute))
            $updated = self::t('messages', 'Updated').': '.
                \Yii::$app->formatter->asDatetime(
                    $this->model->{$this->model->updatedAtAttribute}, 'short');

        return strtr('{created}<br/>{updated}', ['{created}'=>$created, '{updated}'=>$updated]);
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
