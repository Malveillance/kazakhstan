<?php

namespace app\controllers;

use Yii;
use app\models\Machine;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\imagine\Image;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class MachineController extends \yii\web\Controller
{
    public $defaultAction = 'list';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['add', 'edit', 'delete'],
                'rules' => [
                    [
                        'actions' => ['add', 'edit', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload-image' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * AJAX upload image.
     * @return string
     */
    public function actionUploadImage()
    {
        $file = UploadedFile::getInstanceByName('Machine[image]');

        $filename = time() . '_' . substr(md5($file->baseName), 0, 16) . '.' . (($file->extension == 'jpeg') ? 'jpg' : $file->extension);

        $path = Yii::$app->params['uploadsPath'] . $filename;
        $thumbpath = Yii::$app->params['thumbsPath'] . $filename;

        if ($file->saveAs($path)) {
            Image::resize($path, 1600, 900)->save($path, ['quality' => 80]);
            Image::thumbnail($path, 100, null)->save($thumbpath, ['quality' => 60]);

            return $this->asJson([
                'imageUrl' => Url::toRoute($path, true),
                'thumbUrl' => Url::toRoute($thumbpath, true),
                'title' => Machine::getImageInfo($path),
                'value' => base64_encode($filename),
            ]);
        }
    }

    /**
     * Lists all Machine models.
     * @return mixed
     */
    public function actionList()
    {
        return $this->render('list', [
            'model' => Machine::find()->select('name, id')->orderBy('name ASC')->all(),
        ]);
    }

    /**
     * Creates a new Machine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new Machine();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Не удалось сохранить данные в базе.'));
            }
        }

        return $this->render('add', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Machine model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Machine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Не удалось сохранить данные в базе.'));
            }
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Machine model.
     * If deletion is successful, the browser will be redirected to the 'list' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['list']);
    }

    /**
     * Finds the Machine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Machine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Machine::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
    }
}
