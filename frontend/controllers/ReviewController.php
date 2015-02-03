<?php

namespace frontend\controllers;
//namespace app\components;

require(__DIR__ . '/../../components/Opinion.php');

use Yii;
use frontend\models\Review;
use frontend\models\ReviewSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use components\Opinion;

/**
 * ReviewController implements the CRUD actions for Review model.
 */
class ReviewController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Review models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $model = new Review();
        $searchModel = new ReviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Review model.
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
     * Creates a new Review model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Review();

        $userID = yii::$app->user->getId();

//        if ($model->load(Yii::$app->request->post())) {
//            $model->userID = yii::$app->user->getId();
//            $model->save();
//            return $this->redirect(['view', 'id' => $model->reviewID]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }

        if ($model->load(Yii::$app->request->post())) {
            $model->userID = $userID;
            $model->save();
            return $this->redirect(['view', 'id' => $model->reviewID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Updates an existing Review model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->reviewID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Review model.
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
     * Finds the Review model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Review the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionOpen()
    {
        $polarity = new Opinion();
        $model = new Review();

//        $reviews = Review::find()
//            ->asArray()
//            ->all();

        $query = 'SELECT * FROM review';
        $reviews = Review::findBySql($query)
            ->asArray()
            ->all();

//        $storeData = json_encode($reviews, JSON_PRETTY_PRINT);
//        $file = './../../components/data/data.json';
//        file_put_contents($file, $storeData);

//        foreach($reviews as $review)
//        {
//            $op->classify($review);
//            $value = array(
//                'string' => $review,
//                'sentiment' => $op
//            );
//        }

        $jsonPosFile = './../../components/data/posdata.json';
        $jsonNegFile = './../../components/data/negdata.json';

        foreach($reviews as $review)
        {
            $reviewID = $review['reviewID'];
            $productID = $review['productID'];
            $review = $review['review'];
            $polarity->classify($review);

            $arrayObject = array(
                'reviewID'  =>  $reviewID,
                'productID' =>  $productID,
                'review'    =>  $review,
                'polarity'  =>  $polarity
            );

            if($polarity == 'pos')
            {
                $jsonPosContent = file_get_contents($jsonPosFile);
                $jsonDecode = json_decode($jsonPosContent, TRUE);
                $arrayValue = array_merge((array)$jsonDecode, $arrayObject);
                $json = json_encode($arrayValue, JSON_PRETTY_PRINT);
                file_put_contents($jsonPosFile,$json);
                unset($json);
            }
            if($polarity == 'neg')
            {
                $jsonNegContent = file_get_contents($jsonNegFile);
                $jsonDecode = json_decode($jsonNegContent, TRUE);
                $arrayValue = array_merge((array)$jsonDecode, $arrayObject);
                $json = json_encode($arrayValue, JSON_PRETTY_PRINT);
                file_put_contents($jsonNegFile,$json);
                unset($json);
            }
        }

//        $string = 'it a nice product with respect to camera.';

//        $result = $polarity->classify($string);

        return $this->render('open',
            [
                'model' => $model,
//                'polarity' => $polarity,
//                'result' => $result,
//                'string' => $string,
                'reviews' => $reviews,
//                'arrayObject' => $arrayObject,
            ]
        );
    }
}
