<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use app\models\DeductionsAllowances;
use app\models\search\DeductionsAllowances as DeductionsAllowancesSearch;

/**
 * DeductionAllowancesController implements the CRUD actions for DeductionsAllowances model.
 */
class DeductionsAllowancesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DeductionsAllowances models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeductionsAllowancesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeductionsAllowances model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DeductionsAllowances model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeductionsAllowances();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DeductionsAllowances model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DeductionsAllowances model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * Gets locations for a branch
     */
    public function actionGetType($type)
    {
        $dedAllow = DeductionsAllowances::find()->where(['type' => $type])->all();
        if (count($dedAllow) == 0) {
           return json_encode(['success'  => 0, 
                              'message'    =>'No deductions or allowances found']);
        } else {
            $data = ArrayHelper::toArray($dedAllow, [
                'app\models\DeductionsAllowances' => [
                    'id',
                    'name'
                ],
            ]);
            return json_encode(['success'=> 1, 
                               'data'   => $data
                         ]);
        }
    }
    
    /**
     * Finds the DeductionsAllowances model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeductionsAllowances the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeductionsAllowances::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
