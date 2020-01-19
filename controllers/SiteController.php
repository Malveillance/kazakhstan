<?php

namespace app\controllers;

use Yii;
use app\models\Login;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SiteController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     * @return Response
     */
    public function actionLogin()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Login();

            if ($model->load(Yii::$app->request->post())) {
                return $model->login() ? $this->goHome() : $this->asJson(ActiveForm::validate($model));
            }
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }

    /**
     * Logout action.
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
