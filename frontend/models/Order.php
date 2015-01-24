<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "order".
 *
 * @property integer $orderID
 * @property integer $userID
 * @property integer $productID
 * @property string $record
 *
 * @property User $user
 * @property Product $product
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'productID', 'record'], 'required'],
            [['userID', 'productID'], 'integer'],
            [['record'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orderID' => 'Order ID',
            'userID' => 'User ID',
            'productID' => 'Product ID',
            'record' => 'Record',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['productID' => 'productID']);
    }
}
