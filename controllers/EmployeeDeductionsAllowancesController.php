<?php

namespace app\controllers;

use Yii;
use app\models\EmployeeDeductionsAllowances;
use app\models\search\EmployeeDeductionsAllowances as EmployeeDeductionsAllowancesSearch;
use app\models\UpdateAllDeductionsAllowancesUI;
use app\models\Employee;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;
/**
 * EmployeeDeductionsAllowancesController implements the CRUD actions for EmployeeDeductionsAllowances model.
 */
class EmployeeDeductionsAllowancesController extends Controller
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
     * Lists all EmployeeDeductionsAllowances models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeDeductionsAllowancesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EmployeeDeductionsAllowances model.
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
     * Creates a new EmployeeDeductionsAllowances model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmployeeDeductionsAllowances();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EmployeeDeductionsAllowances model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $employeeId
     * @return mixed
     */
    public function actionUpdate($type, $employeeId)
    {
        $models = $this->findModels($type, $employeeId);
        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models))
        {
            foreach ($models as $model) {
                $model->updated_at = time();
                $model->save(false);
            }
            return $this->redirect(['update', 'type' => $type, 'employeeId' => $employeeId]);
        }
        else {
            return $this->render('update', [
                'models' => $models,
                'employeeId' => $employeeId,
                'type'  => $type
            ]);
        }
    }

    public function actionUpdateAll()
    {
        $model = new UpdateAllDeductionsAllowancesUI();

        if ($model->load(Yii::$app->request->post())) {
            
            //Get all the employees
            if ($model->location_id == 0) { 
                $employees = Employee::find()->where(['branch_id' => $model->branch_id])->all();
            } else {
                $employees = Employee::find()->where(['location_id' => $model->location_id])->all();
            }
            //Update records
            foreach ($employees as $employee) {
                $empDedAllow = EmployeeDeductionsAllowances::find()->where([
                    'deductions_allowances_id' => $model->deductions_allowances_id, 
                    'employee_id' => $employee->id])->one();

                if ($empDedAllow == NULL) {
                    $empDedAllow = new EmployeeDeductionsAllowances();
                    $empDedAllow->employee_id = $employee->id;
                    $empDedAllow->deductions_allowances_id = $model->deductions_allowances_id;
                    $empDedAllow->amount = $model->amount;
                    $empDedAllow->save();
		    if ($empDedAllow->errors)
                	throw new \Exception(print_r($empDedAllow->errors, true));

                } else {
                    $empDedAllow->amount = $model->amount;
		    $empDedAllow->save();
                    if ($empDedAllow->errors)
                	throw new \Exception(print_r($empDedAllow->errors, true));
                }
           } 
            $session = Yii::$app->session;
            $session->setFlash('success', 'Successfully updated');
            $model = new UpdateAllDeductionsAllowancesUI();
        } 
	    return $this->render('update-all', [
					      'model' => $model]);
    }
    
    /**
     * Deletes an existing EmployeeDeductionsAllowances model.
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
     * Finds the EmployeeDeductionsAllowances model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmployeeDeductionsAllowances the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModels($type, $employeeId)
    {
        $datable = "deductions_allowances_" . Yii::$app->session['entity_id'];
        $edatable = "employee_deductions_allowances_" . Yii::$app->session['entity_id'];
        
        $model = EmployeeDeductionsAllowances::find()
                    ->innerJoin($datable,
                        "{$edatable}.deductions_allowances_id = {$datable}.id")
                    ->where(['employee_id' => $employeeId, 'type' => $type])
                    ->all();
            
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

     /**
     * Finds all the EmployeeDeductionsAllowances model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PetrolstationProductLoad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAll($type)
    {
        if (($model = EmployeeDeductionsAllowances::find()->where(['type' => $type])->all()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
