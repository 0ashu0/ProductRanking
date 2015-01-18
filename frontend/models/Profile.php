<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "profile".
 *
 * @property integer $profileID
 * @property integer $userID
 * @property string $firstName
 * @property string $middleName
 * @property string $lastName
 * @property string $gender
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'firstName', 'middleName', 'lastName', 'gender'], 'required'],
            [['userID'], 'integer'],
            [['gender'], 'string'],
            [['firstName', 'middleName', 'lastName'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'profileID' => 'Profile ID',
            'userID' => 'User ID',
            'firstName' => 'First Name',
            'middleName' => 'Middle Name',
            'lastName' => 'Last Name',
            'gender' => 'Gender',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userID']);
    }
}
