<?php

namespace budanoff\synchuser\controllers;


use budanoff\synchuser\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\rbac\PhpManager;
use yii\web\Controller;

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
     *
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function actionIndex()
    {
        $secret_key = Yii::$app->request->post('secret_key');
        if ($secret_key != $this->module->secret_key) {
            return Json::encode(["result"=>0, "message"=>"403 forbidden"]);
        }

        $username = Yii::$app->request->post('username');
        $user = User::find()->where(["username"=>$username])->one();

        if (empty($user)) {
            $user = new User();
        }

        $user->load(Yii::$app->request->post(), '');

        if (!$user->save()) {
            return Json::encode(["result"=>0, "message"=>"400 Can not save"]);
        }

        $access = Yii::$app->request->post('access');
        $role = $this->module->role;
        $role_name = (array_key_exists($access, $role))?$role[$access]:$access;

        if ($this->module->reload_role) {
            $this->reloadRole($user->id, $role_name);
        } else {
            $this->checkRole($user->id, $role_name);
        }

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
            if (is_array($role_name)) {
                foreach ($role_name as $role_name_item) {
                    $role->assign($role->getRole($role_name_item), $id_user);
                }
            } else {
                $role->assign($role->getRole($role_name), $id_user);
            }
        }
    }

    /**
     * @param $id_user integer
     * @param $role_name string
     * @throws \Exception
     */
    public function reloadRole($id_user, $role_name)
    {
        $role =  new PhpManager();
        $role->revokeAll($id_user);

        if (is_array($role_name)) {
            foreach ($role_name as $role_name_item) {
                $role->assign($role->getRole($role_name_item), $id_user);
            }
        } else {
            $role->assign($role->getRole($role_name), $id_user);
        }
    }
}
