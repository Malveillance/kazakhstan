<?php

namespace app\controllers;

use Yii;
use app\models\Password;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\filters\VerbFilter;

class UserController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'change-password' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Change password action.
     */
    public function actionChangePassword()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Password();

            if ($model->load(Yii::$app->request->post())) {
                return $model->change() ? $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl) : $this->asJson(ActiveForm::validate($model));
            }
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }
}
