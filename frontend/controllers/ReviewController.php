<?php

namespace frontend\controllers;
//namespace app\components;

require(__DIR__ . '/../../components/Opinion.php');
require(__DIR__ . '/../../components/pos/POSTag.php');

use Yii;
use frontend\models\Review;
use frontend\models\ReviewSearch;
use frontend\models\Product;
use frontend\models\ProductSearch;
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

        $query = 'SELECT * FROM product';
        $reviewsP = Review::findBySql($query)
            ->asArray()
            ->all();

        $storeDataP = json_encode($reviewsP, JSON_PRETTY_PRINT);
        $file = './../../components/data/product.json';
        file_put_contents($file, $storeDataP);

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
                        'review' => $review,
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

        foreach ($jsonDecode as $array)
        {
            $userID = $array['userID'];
            $reviewID = $array['reviewID'];
            $productID = $array['productID'];
            $review = $array['review'];
            $currentSentence = $array['Sentence'];

            $po = $array['Sentence']['0'];
            $posArray = $pos->createTag($po);

            $currentArray = array(
                'reviewID' => $reviewID,
                'userID' => $userID,
                'productID' => $productID,
                'review' => $review,
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
                'bigArray' => $bigArray,
            ]
        );
    }

    public function actionAdj()
    {
        $answer = array();
        $answerArray = array();
        $jsonPosFilePScore = './../../components/data/posdataPOS.json';
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

//            echo '<pre>', var_dump($posArray), '<pre/>';

            foreach($posArray as $tags)
            {
                if($tags['tag']=="JJ" || $tags['tag']=="JJ\r\n" || $tags['tag'] == "JJR" || $tags['tag']== "JJS")
                {
                    $answer = $tags['token'];
                    if (!(in_array($answer, $answerArray)))
                        $answerArray[] = $answer;
                }
            }

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

    public function actionPoscore()
    {
        $posScore = array();

        $adjectiveScore = 0;

        $jsonPosFileP = './../../components/data/posdataPOS.json';

        $jsonPosFilePScore = './../../components/data/posdataPOSScore.json';

//        $adjectives = './../../components/data/adj_db.json';

        $jsonContent = file_get_contents($jsonPosFileP);
        $jsonDecode = json_decode($jsonContent, TRUE);

        $bigArray = array ();
        foreach ($jsonDecode as $array)
        {
            $posScoreArray = array();
            $userID = $array['userID'];
            $reviewID = $array['reviewID'];
            $productID = $array['productID'];
            $currentSentence = $array['Sentence'];
            $posArray = $array['posArray'];
//            echo '<pre>', var_dump($posArray), '<pre/>';

            // this is one tags array
            foreach($posArray as $tags)
            {
                // this is one tags key value pair
                foreach($tags as $key => $value)
                {
                    if($key == 'tag')
                    {
                        if ($value == "JJ" || $value == "JJ\r\n")
                        {
                            $posScore = array($tags['token'], 0.2);
                            $posScoreArray[] = $posScore;
                            unset($posScore);
                        }
                        else if ($value == "JJR")
                        {
                            $posScore = array($tags['token'], 0.4);
                            $posScoreArray[] = $posScore;
                            unset($posScore);
                        }
                        else if ($value == "JJS")
                        {
                            $posScore = array($tags['token'], 0.6);
                            $posScoreArray[] = $posScore;
                            unset($posScore);
                        }
                    }
                }
            }

            $currentArray = array(
                'reviewID' => $reviewID,
                'userID' => $userID,
                'productID' => $productID,
                'Sentence' => $currentSentence,
                'posArray' => $posArray,
                'posScoreArray' => $posScoreArray
            );
            $bigArray[] = $currentArray;

            unset($posScoreArray);
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
                'bigArray' => $bigArray,
            ]
        );
    }

    public function actionIdfcr()
     {
         $bigArray = array ();
         $counter = 0;

         /*****************************************/
 //        $answer = array();
 //        $answerArray = array();
         $adjectiveScore = 0;

         $jsonPosFilePScore = './../../components/data/data.json';

         $adjectives = './../../components/data/adj_db.json';

         $ReviewFile = './../../components/data/expand.json';

         /* output */
         $adjectiveCounter = './../../components/data/adj_db_counter.json';

         $jsonPosContent = file_get_contents($jsonPosFilePScore);
         $jsonPosDecode = json_decode($jsonPosContent, TRUE);

         $jsonAdjContent = file_get_contents($adjectives);
         $jsonAdjDecode = json_decode($jsonAdjContent, TRUE);

         $jsonReviewContent = file_get_contents($ReviewFile);
         $jsonReviewDecode = json_decode($jsonReviewContent, TRUE);

         $count = 0;
         foreach($jsonReviewDecode as $review)
         {
             $count = $count + 1;
             echo $count;
         }

         foreach($jsonAdjDecode as $adj)
         {
             $counter = 0;
             foreach($jsonPosDecode as $array)
             {
                 $idf = 0;
                 $reviewID = $array['reviewID'];
                 $sentence = $array['review'];

                 if (strpos($sentence,$adj) !== false)
                 {
//                     echo "hit found";
                     $counter = $counter + 1;
//                     echo $counter . "inside";
                 }
             }

             $idf = log10($count/$counter) * 1/log10($count);
             echo $idf;
//             echo $counter;

             $adjArray = array(
                 'adj' => $adj,
                 'CR' => $counter,
                 'TR' => $count,
                 'IDF' => $idf
                 );

             $bigArray[] = $adjArray;

//             echo '<pre>', var_dump($bigArray), '<pre/>';
         }

         $json = json_encode($bigArray, JSON_PRETTY_PRINT);
         file_put_contents($adjectiveCounter,$json);

         /*****************************************/

         return $this->render('idfcr',
             [
                 'bigArray' => $bigArray,
             ]
         );
     }

    public function actionSentence()
    {
        $jsonPosFilePScore = './../../components/data/posdataPOSScore.json';
        $adjectiveCounter = './../../components/data/adj_db_counter.json';
        $posdataSentence = './../../components/data/posdataSentence.json';

        $jsonContent = file_get_contents($jsonPosFilePScore);
        $jsonDecode = json_decode($jsonContent, TRUE);

        $jsonAdjContent = file_get_contents($adjectiveCounter);
        $jsonAdjDecode = json_decode($jsonAdjContent, TRUE);

        $bigArray = array ();
        foreach($jsonDecode as $array)
        {
            $userID = $array['userID'];
            $reviewID = $array['reviewID'];
            $productID = $array['productID'];
            $sentence = $array['Sentence']['0'];
            $posScoreArray = $array['posScoreArray'];

//            echo '<pre>', var_dump($sentence), '<pre/>';
//            echo '<pre>', var_dump($posScoreArray), '<pre/>';
//            echo '<pre>', var_export($jsonAdjDecode), '<pre/>';

            $eval = 0;
            foreach($posScoreArray as $adjective)
            {

                foreach($jsonAdjDecode as $matches)
                {
                    if($adjective['0'] == $matches['adj'])
                    {
//                        echo "true";
                        $eval = $eval +  $adjective['1'] * $matches['IDF'];
                    }
                }
            }
//            echo $eval;
//            echo '<br>';

            $currentArray = array(
                'reviewID' => $reviewID,
                'userID' => $userID,
                'productID' => $productID,
                'Sentence' => $sentence,
                'sentenceWeight' => $eval
            );
            $bigArray[] = $currentArray;
        }

        $json = json_encode($bigArray, JSON_PRETTY_PRINT);
        file_put_contents($posdataSentence,$json);

        return $this->render('sentence',
            [

            ]
        );
    }

    public function actionSentencewt()
    {
        $posdataSentence = './../../components/data/posdataSentence.json';
        $posdataSentencewt = './../../components/data/posdataSentencewt.json';

        $jsonContent = file_get_contents($posdataSentence);
        $jsonDecode = json_decode($jsonContent, TRUE);

        $bigArray = array();
        $value = 1;
        $reviewWeight = 0;
        foreach ($jsonDecode as $array) {
            $userID = $array['userID'];
            $reviewID = $array['reviewID'];
            $productID = $array['productID'];
            $sentence = $array['Sentence'];
            $sentenceWeight = $array['sentenceWeight'];

            if ($reviewID != $value) {
                $value = $value + 1;
                $reviewWeight = 0;
            }

            $reviewWeight = $reviewWeight + $sentenceWeight;

            $currentArray = array(
                'reviewID' => $reviewID,
                'userID' => $userID,
                'productID' => $productID,
                'Sentence' => $sentence,
                'reviewWeight' => $reviewWeight
            );
            $bigArray[] = $currentArray;
        }

        $json = json_encode($bigArray, JSON_PRETTY_PRINT);
        file_put_contents($posdataSentencewt, $json);

        return $this->render('sentence',
            [

            ]
        );
    }

    public function actionReviewwt()
    {
        $currentArray = array();
        $bigArray = array();
        $posdataSentencewt = './../../components/data/posdataSentencewt.json';

        $reviewWeightFile = './../../components/data/reviewWeightFile.json';

        $jsonContent = file_get_contents($posdataSentencewt);
        $jsonDecode = json_decode($jsonContent, TRUE);

        $maxID = 0;
        foreach($jsonDecode as $array)
        {
            $reviewID = $array['reviewID'];
            if($maxID < $reviewID)
                $maxID = $reviewID;
        }

        $a=0;
        $i=1;

        for($i=1;$i<=$maxID;$i++)
        {
            $a=0;
            foreach ($jsonDecode as $array)
            {
                $userID = $array['userID'];
                $reviewID = $array['reviewID'];
                $productID = $array['productID'];
                $sentence = $array['Sentence'];
                $reviewWeight = $array['reviewWeight'];

                foreach ($array as $entries) {
                    if ($entries == $reviewWeight && $reviewID == $i && $entries > $a)
                    {
                        $a = $entries;
                        $currentArray = array(
                            'reviewID' => $reviewID,
                            'userID' => $userID,
                            'productID' => $productID,
                            'reviewWeight' => $a
                        );
                    }
                }
                //   echo $a;
            }
            $bigArray[] = $currentArray;
            echo "something";
            echo $a;
            echo '<br>';
        }

        var_dump($bigArray);

        $json = json_encode($bigArray, JSON_PRETTY_PRINT);
        file_put_contents($reviewWeightFile, $json);

        return $this->render('sentence',
            [

            ]
        );
    }

    public function actionProduct()
    {
        $reviewWeightFile = './../../components/data/reviewWeightFile.json';

        $productRank = './../../components/data/productRank.json';

        $jsonContent = file_get_contents($reviewWeightFile);
        $jsonDecode = json_decode($jsonContent, TRUE);

        $max = 4;
        foreach($jsonDecode as $array)
        {
            $productID = $array['productID'];
            if($max<$productID)
                $max = $productID;
        }
        $bigArray = array();
        for($i=1;$i<=$max;$i++)
        {
            $score = 0;
            foreach($jsonDecode as $array)
            {
                $productID = $array['productID'];
                $reviewWeight = $array['reviewWeight'];

                if($productID == $i)
                {
                    $score = $score + $reviewWeight;
                }
            }
//            echo $i;
//            echo '<br>';
//            echo $score;
//            echo '<br>';
            $currentArray = array(
                'productRank' => $score,
                'productID' => $i
            );
            $bigArray[] = $currentArray;
        }
        /*
         * descending logic here
         */

        arsort($bigArray);

//        echo '<pre>', var_export($bigArray), '<pre/>';
        $json = json_encode($bigArray, JSON_PRETTY_PRINT);
        file_put_contents($productRank, $json);

        return $this->render('sentence',
            [

            ]
        );
    }

    public function actionResult()
    {
        $array;
        $bigArray = array();
        $dataFile = './../../components/data/product.json';
        $dataContent = file_get_contents($dataFile);
        $dataDecode = json_decode($dataContent, TRUE);

        $productRank = './../../components/data/productRank.json';
        $productContent = file_get_contents($productRank);
        $productDecode = json_decode($productContent, TRUE);

        foreach($productDecode as $product)
        {
            $productRank = $product['productRank'];
            $productID = $product['productID'];

            foreach($dataDecode as $entry)
            {
                $prodID = $entry['productID'];
                $prodName = $entry['name'];
                $prodPrice = $entry['price'];

                if($productID == $prodID)
                {
                    $array = array($prodID, $prodName, $prodPrice);
                }
            }
            $bigArray[] = $array;
        }

//        var_export($bigArray);

        return $this->render('result',
            [
                'bigArray' => $bigArray,
            ]
        );
    }
}

