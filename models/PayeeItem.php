<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "payee_item_1".
 *
 * @property integer $id
 * @property string $name
 * @property string $payee_item_type
 * @property string $payee_item_group
 * @property string $amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class PayeeItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payee_item_'  . Yii::$app->session['entity_id'];
        //return 'payee_item_1';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'payee_item_type', 'payee_item_group', 'amount'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'payee_item_type' => 'Payee Item Type',
            'payee_item_group' => 'Payee Item Group',
            'amount' => 'Value', //Todo: change to value
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
