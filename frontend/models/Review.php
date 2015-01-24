<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "review".
 *
 * @property integer $reviewID
 * @property integer $userID
 * @property integer $productID
 * @property string $review
 *
 * @property User $user
 * @property Product $product
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'productID', 'review'], 'required'],
            [['userID', 'productID'], 'integer'],
            [['review'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reviewID' => 'Review ID',
            'userID' => 'User ID',
            'productID' => 'Product ID',
            'review' => 'Review',
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
