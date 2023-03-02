<?php

namespace app\controllers;

use Yii;
use app\models\PayrollReport;
use app\models\Payroll;
use app\models\Entity;
use app\models\DeductionsAllowances;
use app\models\Location;
use app\models\Bank;
use app\models\Branch;
use app\models\Employee;
use app\models\UploadPayrollUI;
use app\models\DeductionsReportUI;
use app\models\BankScheduleUI;
use app\models\LoginForm;
use app\models\ReportUI;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BankController implements the CRUD actions for Bank model.
 */
class ReportController extends Controller
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
     * Payroll report
     * @return mixed
     */
    public function actionPayrollReport()
    {
        $model = new PayrollReport();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
              
            LoginForm::setYearMonth($model->date);
            $date = Yii::$app->session['year_id'] . "-" . Yii::$app->session['month_id'] . "-01";
             
            $entity = Entity::findOne(['id' => Yii::$app->session['entity_id']]);
                
            $deductions = DeductionsAllowances::findAll(['type' => 'Deductions']);
            $allowances = DeductionsAllowances::findAll(['type' => 'Allowances']);
                
            //Determine with location to process
            if ($model->location_id != 0) {
                $locations = Location::findAll($model->location_id);
            } else {
                $locations = Location::findAll(['branch_id' => $model->branch_id]);
            }
            $banks = ($model->bank_id != 0) ? Bank::findOne($model->bank_id) : Bank::find()->all();            

            //Check if any output
            if ($model->branch_id == 0) {
                $branches = Branch::find()->all();
            } else {
                $branches = Branch::findAll(['id' => $model->branch_id]);
            }
            $data = [];
            foreach ($branches as $branch) {
                $outputs = Payroll::getPayrollReport($date, 
                      $branch->id, $model->location_id, $model->bank_id);
                if (count($outputs) > 0)
                  $data[] = $outputs;
            }
          
            if (count($data) == 0) {
                Yii::$app->session->setFlash('error', "No data found");
            }
            return $this->render('payroll-report-response', [
                'model' => $model,
                'entity' => $entity,
                'locations' => $locations, 
                'banks' => $banks,
                //'employees' => $employees,
                'deductions' => $deductions, 
                'allowances' => $allowances,
                'data' =>    $data,
                'date'      => $date
            ]);
        } else {
            return $this->render('payroll-report-request', [
                'model' => $model,
            ]);
        }
    }
    
    private function uploadPayrollSchedule($model)
    {  
        $model->payrollFile = UploadedFile::getInstance($model, 'payrollFile');
        $payrollFilename = null;
        
        if ($model->payrollFile) {
            $payrollFilename = 'uploads/' . rand(5, 158997) . '_' . $model->payrollFile->baseName . '.'  . $model->payrollFile->extension;
            $model->payrollFile->saveAs($payrollFilename);
        }
        return $payrollFilename;
    }
        
    /**
     * Upload a CSV containing payroll
     * @return mixed
     */
    public function actionUploadPayroll()
    {
        $model = new UploadPayrollUI();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) { 
            
            LoginForm::setYearMonth($model->date);
            $date = Yii::$app->session['year_id'] . "-" . Yii::$app->session['month_id'] . "-01";
                       
            $actual = Payroll::uploadPayroll( $this->uploadPayrollSchedule($model), $date, $model->branch_id); 
            return $this->render('upload-payroll-response', []);
        }
        return $this->render('upload-payroll-request', [
                'model' => $model,
            ]);
    }
    
    /**
     * Deductions reports
     * @return mixed
     */
    public function actionDeductionsReport()
    {
        $model = new DeductionsReportUI();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {  
            
            LoginForm::setYearMonth($model->date);
            $date = Yii::$app->session['year_id'] . "-" . Yii::$app->session['month_id'] . "-01";
           
            
            $payroll = Payroll::find()->where(['payroll_date' => $date])->one();
            $entity = Entity::find()->where(['id' => Yii::$app->session['entity_id']])->one();
            $deduction = DeductionsAllowances::find()->where(['id' => $model->deductions_allowances_id])->one();
            
            if ($model->branch_id == 0)
                $branch = Branch::find()->all();
            else
                $branch = Branch::find()->where(['id' => $model->branch_id])->one();
            
            if ($model->location_id == 0) {
                if ($model->branch_id == 0)
                    $locations = Location::find()->all();
                else 
                    $locations = Location::find()->where(['branch_id' => $model->branch_id])->all();
            } else {
                $locations = [Location::find()->where(['id' => $model->location_id])->one()];    
            }
            
            return $this->render('deductions-report-response', [
                'model'     => $model, 
                'payroll'   => $payroll,
                'entity'    => $entity,
                'branch'    => $branch,
                'locations' => $locations,
                'deduction' => $deduction]);
        }
        return $this->render('deductions-report-request', [
                'model' => $model,
            ]);
    }
    
    /**
     * Bank Schedule Report
     * @return mixed
     */
    public function actionBankSchedule()
    {
        $model = new BankScheduleUI();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {    
            
            LoginForm::setYearMonth($model->date);
            $date = Yii::$app->session['year_id'] . "-" . Yii::$app->session['month_id'] . "-01";          
            $entity = Entity::findOne(['id' => Yii::$app->session['entity_id']]);
               
            //Determine with location to process
            if ($model->location_id != 0) {
                $locations = Location::findAll($model->location_id);
            } else {
                $locations = Location::findAll(['branch_id' => $model->branch_id]);
            }
            $banks = ($model->bank_id != 0) ? Bank::findOne($model->bank_id) : Bank::find()->all();            

            //Check if any output
            if ($model->branch_id == 0) {
                $branches = Branch::find()->all();
            } else {
                $branches = Branch::findAll(['id' => $model->branch_id]);
            }

            $data = [];
            foreach ($branches as $branch) {
                $outputs = Payroll::getPayrollReport($date, 
                      $branch->id, $model->location_id, $model->bank_id);
                if (count($outputs) > 0)
                  $data[] = $outputs;
            }

            if (count($data) == 0) {
                Yii::$app->session->setFlash('error', "No data found");
            }                       
            return $this->render('bank-schedule-response', [
                'model'     => $model,
                'entity'    => $entity,
                'locations' => $locations, 
                'banks'     => $banks,
                'data'      => $data,
                'date'      => $date
            ]);
          
        }
        return $this->render('bank-schedule-request', [
                'model' => $model,
            ]);
    }
    
     public function actionPayslip()
    {
        $model = new ReportUI();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                          
            LoginForm::setYearMonth($model->date);
            $date = Yii::$app->session['year_id'] . "-" . Yii::$app->session['month_id'] . "-01";
             
            $entity = Entity::findOne(['id' => Yii::$app->session['entity_id']]);
               
            //Determine with location to process
            if ($model->location_id != 0) {
                $locations = Location::findAll($model->location_id);
            } else {
                $locations = Location::findAll(['branch_id' => $model->branch_id]);
            }
        
            $deductions = DeductionsAllowances::findAll(['type' => 'Deductions']);
            $allowances = DeductionsAllowances::findAll(['type' => 'Allowances']);
            
            //Check if any output
            $outputs = Payroll::getPayrollReport($date, 
                      $model->branch_id, $model->location_id, 0);

            if (count($outputs) == 0) {
                Yii::$app->session->setFlash('error', "No data found");
            }
            
            return $this->render('payslip-response', [
                'model' => $model,
                'entity' => $entity,
                'locations' => $locations, 
                'allowances' => $allowances,
                'deductions' => $deductions,
                'outputs' =>    $outputs,
                'date'      => $date
            ]);
          
        } else {
            return $this->render('payslip-request', [
                'model' => $model,
            ]);
        }
    }
}