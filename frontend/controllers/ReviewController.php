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

    /*
     * This functions accepts file, and array and print the json data to the file
     */
    protected function jsonPrinter($filename, $array)
    {
        $jsonContent = file_get_contents($filename);
        $jsonDecode = json_decode($jsonContent, TRUE);
        $json = json_encode($array, JSON_PRETTY_PRINT);
        file_put_contents($filename,$json);
        unset($filename);
        unset($jsonContent);
        unset($jsonDecode);
        unset($json);
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

        $storeData = json_encode($reviews, JSON_PRETTY_PRINT);
        $file = './../../components/data/data.json';
        file_put_contents($file, $storeData);


        $jsonPosFile = './../../components/data/posdata.json';
        $jsonNegFile = './../../components/data/negdata.json';
        $expandedFile = './../../components/data/expand.json';

        $bigArray = array ();
        $result = array();
        $allReviews = array();
        $positiveResult = array();
        $negativeResult = array();

        foreach($reviews as $review)
        {
            $userID = $review['userID'];
            $reviewID = $review['reviewID'];
            $productID = $review['productID'];
            $review = $review['review'];

            $rev = explode(". ", $review);
            $nameTypes    = array("first", "second", "third", "fourth", "fifth", "sixth", "seventh", "eighth");

            $count = 0;
            /*
             * isolating lines from the review
             */
            foreach($rev as $single)
            {
                if(isset($single))
                {
                    ${$nameTypes[$count]} = $single; // variables $first, $second
                }
                $count = $count + 1;
            }

            $count = 0;

            /*
             * finding polarity of the line and creating an array
             */
            foreach($nameTypes as $index)
            {
                if(isset(${$nameTypes[$count]})) // ${$index}
                {
                    // echo $nameTypes[$count];
                    // echo ${$nameTypes[$count]};
                    $thisPolarity = $polarity->classify(${$nameTypes[$count]});

                    $currentSentence = array(${$nameTypes[$count]}, $thisPolarity);
                    $currentArray = array($nameTypes[$count] => $currentSentence);

                    $result = array($currentArray);
                    $allReviews = array_merge($allReviews, $currentArray);
//                    echo '<pre>', var_dump($result), '<pre/>';

                    $polarityArray = array(
                        'reviewID' => $reviewID,
                        'userID' => $userID,
                        'productID' => $productID,
                        'review' => $review,
                        'result' => $result
                    );

//                    echo '<pre>', var_dump($polarityArray), '<pre/>';

                    if($thisPolarity == 'pos')
                    {
                        $positiveResult[] = $polarityArray;
                    }
                    if($thisPolarity == 'neg')
                    {
                        $negativeResult[] = $polarityArray;
                    }
                }
                $count = $count + 1;
            }


            foreach($nameTypes as $index)
            {
                unset(${$index});
            }

            echo '<br>';

            $arrayObject = array(
                'reviewID' => $reviewID,
                'userID' => $userID,
                'productID' => $productID,
                'review' => $review,
                'allReviews' => $allReviews
            );

            $bigArray[] = $arrayObject;
        }


        $this->jsonPrinter($jsonPosFile, $positiveResult);
        $this->jsonPrinter($jsonNegFile, $negativeResult);

        $this->jsonPrinter($expandedFile, $bigArray);

        return $this->render('open',
            [
                'model' => $model,
                'reviews' => $reviews,
                'bigArray' => $bigArray,
            ]
        );
    }
}
