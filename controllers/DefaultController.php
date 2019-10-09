<?php

namespace budanoff\synchuser\controllers;


use budanoff\synchuser\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\rbac\PhpManager;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `synchuser` module
 */
class DefaultController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'  => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return Json
     * @throws BadRequestHttpException*@throws \yii\base\Exception
     *
     * @throws ForbiddenHttpException
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        $secret_key = Yii::$app->request->post('secret_key');
        if ($secret_key != $this->module->secret_key) {
            throw new ForbiddenHttpException("forbidden".$secret_key."_");
        }

        $username = Yii::$app->request->post('username');
        $user = User::find()->where(["username"=>$username])->one();

        if (empty($user)) {
            $user = new User();
        }

        $user->load(Yii::$app->request->post(), '');

        if (!$user->save()) {
            throw new BadRequestHttpException("Can not save");
        }

        $access = Yii::$app->request->post('access');
        $role_name = ($access=='podved' or $access=='other_podved')?"user":$access;
        $this->checkRole($user->id, $role_name);

        return Json::encode(["result"=>1]);
    }

    /**
     * @param $id_user integer
     * @param $role_name string
     * @throws \Exception
     */
    public function checkRole($id_user, $role_name)
    {
        $role =  new PhpManager();
        $user = $role->getRolesByUser($id_user);
        if (count($user)==0) {
            $role->assign($role->getRole($role_name), $id_user);
        }
    }
}
