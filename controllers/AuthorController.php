<?php

namespace app\controllers;

use Yii;
use app\models\Author;
use app\models\AuthorSearch;
use app\models\AuthorSubscription;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class AuthorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'subscribe', 'top'],
                        'allow' => true,
                        'roles' => ['?', '@'], // Гости и пользователи
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'], // Только авторизованные пользователи
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $subscription = new AuthorSubscription();
        $subscription->author_id = $id;

        return $this->render('view', [
            'model' => $model,
            'subscription' => $subscription,
        ]);
    }

    public function actionCreate()
    {
        $model = new Author();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Автор успешно создан.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Автор успешно обновлен.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Проверяем, есть ли связанные книги
        if ($model->books) {
            Yii::$app->session->setFlash('error', 'Нельзя удалить автора, у которого есть книги. Сначала удалите или измените связанные книги.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Автор успешно удален.');
        return $this->redirect(['index']);
    }

    public function actionTop($year = null)
    {
        $year = $year ? (int)$year : date('Y');
        if ($year < 1900 || $year > date('Y')) {
            $year = date('Y');
        }

        $top = Author::getTop($year, 10);

        return $this->render('top', [
            'top' => $top,
            'year' => $year,
        ]);
    }

    public function actionSubscribe($author_id)
    {
        if (Yii::$app->user->isGuest) {
            $model = new AuthorSubscription();
            $model->author_id = $author_id;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Вы успешно подписались на уведомления о новых книгах автора.');
                return $this->redirect(['author/view', 'id' => $author_id]);
            }

            $author = Author::findOne($author_id);
            if (!$author) {
                throw new NotFoundHttpException('Автор не найден.');
            }

            return $this->render('subscribe', [
                'model' => $model,
                'author' => $author,
            ]);
        } else {
            Yii::$app->session->setFlash('info', 'Авторизованные пользователи могут добавлять книги самостоятельно.');
            return $this->redirect(['author/view', 'id' => $author_id]);
        }
    }

    protected function findModel($id)
    {
        if (($model = Author::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый автор не найден.');
    }
}