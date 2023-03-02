<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;

abstract class AbstractController extends Controller {

    public function beforeAction($action) {
        
        $session = Yii::$app->session;
        //if (!Yii::$app->user->isGuest) {
        
        if ($session['entity_id'] == NULL) { 
            Yii::$app->user->logout(true);
            Yii::$app->user->loginUrl = "../site/login";
            Yii::$app->user->loginRequired();
        } else {
            //$this->layout = "@app/views/layouts/main2";
            $this->layout = "main.php";
            Yii::$app->name = 'HyperPayroll';
            return parent::beforeAction($action);
        }
    }
}
