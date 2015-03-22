<?php

namespace frontend\controllers;
//namespace app\components;

require(__DIR__ . '/../../components/Opinion.php');
require(__DIR__ . '/../../components/pos/POSTag.php');

use Yii;
use frontend\models\Review;
use frontend\models\ReviewSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use components\Opinion;
use components\POSTag;

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
                    $thisPolarity = $polarity->classify(${$nameTypes[$count]});     // polarity of sentence

                    $currentSentence = array(${$nameTypes[$count]}, $thisPolarity); // array of sentence and polarity
                    $currentArray = array($nameTypes[$count] => $currentSentence);

                    $result = array($currentArray);
                    $allReviews = array_merge($allReviews, $currentArray);
//                    echo '<pre>', var_dump($result), '<pre/>';

//                    $polarityArray = array(
//                        'reviewID' => $reviewID,
//                        'userID' => $userID,
//                        'productID' => $productID,
//                        'review' => $review,
//                        'result' => $result
//                    );

                    $polarityArray = array(
                        'reviewID' => $reviewID,
                        'userID' => $userID,
                        'productID' => $productID,
                        'Sentence' => $currentSentence
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

    public function actionPos()
    {
        $bigArray = array ();

        $jsonPosFile = './../../components/data/posdata.json';
//        $jsonNegFile = './../../components/data/negdata.json';

        $jsonPosFileP = './../../components/data/posdataPOS.json';
//        $jsonNegFileP = './../../components/data/negdataPOS.json';

        $jsonContent = file_get_contents($jsonPosFile);
        $jsonDecode = json_decode($jsonContent, TRUE);

        $pos = new POSTag();

// echo '<pre>', var_dump($jsonDecode), '<pre/>';

        foreach ($jsonDecode as $array) {
            $userID = $array['userID'];
            $reviewID = $array['reviewID'];
            $productID = $array['productID'];
            $currentSentence = $array['Sentence'];

            $po = $array['Sentence']['0'];
            $posArray = $pos->createTag($po);

            $currentArray = array(
                'reviewID' => $reviewID,
                'userID' => $userID,
                'productID' => $productID,
                'Sentence' => $currentSentence,
                'posArray' => $posArray
            );

            $bigArray[] = $currentArray;
        }
//        echo '<pre>', var_dump($bigArray), '<pre/>';

        $jsonContent = file_get_contents($jsonPosFileP);
        $jsonDecode = json_decode($jsonContent, TRUE);
        $json = json_encode($bigArray, JSON_PRETTY_PRINT);
        file_put_contents($jsonPosFileP,$json);
        unset($jsonPosFileP);
        unset($jsonContent);
        unset($jsonDecode);
        unset($json);

        return $this->render('pos',
            [

            ]
        );
    }

    public function actionPoscore()
    {
//        $answer = array();
//        $answerArray = array();
        $adjectiveScore = 0;

        $jsonPosFileP = './../../components/data/posdataPOS.json';

        $jsonPosFilePScore = './../../components/data/posdataPOSScore.json';

//        $adjectives = './../../components/data/adj_db.json';

        $jsonContent = file_get_contents($jsonPosFileP);
        $jsonDecode = json_decode($jsonContent, TRUE);

        $bigArray = array ();
        foreach ($jsonDecode as $array)
        {
            $userID = $array['userID'];
            $reviewID = $array['reviewID'];
            $productID = $array['productID'];
            $currentSentence = $array['Sentence'];
            $posArray = $array['posArray'];
//            echo '<pre>', var_dump($posArray), '<pre/>';

            foreach($posArray as $tags)
            {
                foreach($tags as $key => $value)
                {
                    if($key == 'tag')
                    {
                        if ($value == 'JJ')
                            $adjectiveScore = 1;
                        if ($value == 'JJR')
                            $adjectiveScore = 2;
                        if ($value == 'JJS')
                            $adjectiveScore = 3;
                    }
                }
//                if($tags['tag']=='JJ' || $tags['tag'] == 'JJR' || $tags['tag']== 'JJS')
//                {
//                    $answer = $tags['token'];
//                    if (!(in_array($answer, $answerArray)))
//                        $answerArray[] = $answer;
//                }
            }

                $currentArray = array(
                    'reviewID' => $reviewID,
                    'userID' => $userID,
                    'productID' => $productID,
                    'Sentence' => $currentSentence,
                    'posArray' => $posArray,
                    'sentenceScore' => $adjectiveScore
                );
                $bigArray[] = $currentArray;

        }

        $json = json_encode($bigArray, JSON_PRETTY_PRINT);
//        $json2 = json_encode($answerArray, JSON_PRETTY_PRINT);
        file_put_contents($jsonPosFilePScore,$json);
//        file_put_contents($adjectives, $json2);
        unset($jsonPosFileP);
        unset($jsonContent);
        unset($jsonDecode);
        unset($json);

        return $this->render('poscore',
            [

            ]
        );
    }

    public function actionAdj()
    {
        $answer = array();
        $answerArray = array();
        $jsonPosFilePScore = './../../components/data/posdataPOSScore.json';
        $adjectives = './../../components/data/adj_db.json';
        $jsonContent = file_get_contents($jsonPosFilePScore);
        $jsonDecode = json_decode($jsonContent, TRUE);

        foreach ($jsonDecode as $array)
        {
            $userID = $array['userID'];
            $reviewID = $array['reviewID'];
            $productID = $array['productID'];
            $currentSentence = $array['Sentence'];
            $posArray = $array['posArray'];
            $sentenceScore = $array['sentenceScore'];

//            echo '<pre>', var_dump($posArray), '<pre/>';

            foreach($posArray as $tags)
            {
                if($tags['tag']=='JJ' || $tags['tag'] == 'JJR' || $tags['tag']== 'JJS')
                {
                    $answer = $tags['token'];
                    if (!(in_array($answer, $answerArray)))
                        $answerArray[] = $answer;
                }
            }

//            $answerArray = array_unique($answerArray);

//            $currentArray = array(
//                'reviewID' => $reviewID,
//                'userID' => $userID,
//                'productID' => $productID,
//                'Sentence' => $currentSentence,
//                'posArray' => $posArray,
//                'sentenceScore' => $sentenceScore
//            );
//            $bigArray[] = $currentArray;

        }

        $json = json_encode($answerArray, JSON_PRETTY_PRINT);
        file_put_contents($adjectives,$json);
        unset($jsonPosFileP);
        unset($jsonContent);
        unset($jsonDecode);
        unset($json);


        return $this->render('adj',
            [

            ]
        );
    }

    public function actionIdfcr()
    {
        $bigArray = array ();

        /*****************************************/
//        $answer = array();
//        $answerArray = array();
        $adjectiveScore = 0;

        $jsonPosFilePScore = './../../components/data/posdataPOSScore.json';

        $adjectives = './../../components/data/adj_db.json';

        $jsonPosContent = file_get_contents($jsonPosFilePScore);
        $jsonPosDecode = json_decode($jsonPosContent, TRUE);

        $jsonAdjContent = file_get_contents($adjectives);
        $jsonAdjDecode = json_decode($jsonAdjContent, TRUE);

        foreach($jsonAdjDecode as $adj)
        {
            $counter = 0;
            foreach($jsonPosDecode as $array)
            {
                $reviewID = $array['reviewID'];
                $sentence = $array['Sentence'];

                if(in_array($adj, $sentence)
                {
                    //do something
                    $counter = $counter + 1;
                    echo " counter " . $counter;
                }
            }

            $adjArray = array(
                'adj' => $adj,
                'CR' => $counter
                );

            $bigArray[] = $adjArray;

//            var_dump($bigArray);
        }

        /*****************************************/

        return $this->render('idfcr',
            [

            ]
        );
    }
}
