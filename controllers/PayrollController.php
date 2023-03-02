<?php

namespace app\controllers;

use Yii;
use app\models\Payroll;
use app\models\ReportUI;
use app\models\search\Payroll as PayrollSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;

/**
 * PayrollController implements the CRUD actions for Payroll model.
 */
class PayrollController extends Controller
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
     * Lists all Payroll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PayrollSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payroll model.
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
     * Creates a new Payroll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReportUI();
         if ($model->load(Yii::$app->request->post())) {
             
            //Avoid doing SQL queries within a loop
            //A common mistake is placing a SQL query inside of a loop. 
            //This results in multiple round trips to the database, and significantly slower scripts. 
            //You can change the loop to build a single SQL query and insert all of your users at once. 
             
            LoginForm::setYearMonth($model->date);
            $output = Payroll::processPayroll($model->date, $model->branch_id, $model->location_id, $model->employee_id);
            return $this->render('process-payroll-response', [
                'date'      => $model->date,
                'branchName' => '',
                'locationName'  => '',
                'output'        => $output
            ]);
        } else {
            return $this->render('process-payroll-request', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Payroll model.
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
     * Deletes an existing Payroll model.
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
     * Finds the Payroll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payroll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payroll::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
