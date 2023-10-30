<?php
namespace frontend\controllers;

use common\components\actions\DeleteAction;
use common\components\actions\ListAction;
use common\components\actions\ViewAction;
use common\components\controllers\BaseController;
use common\components\controllers\IModelController;
use common\models\City;
use common\models\Invite;
use common\models\ObjectRepair;
use common\rbac\Rights;
use frontend\models\InviteChangeEmailForm;
use frontend\models\InviteChangeStatusForm;
use frontend\models\InvitesSearch;
use Exception;
use ReflectionClass;
use Yii;
use yii\db\Connection;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use frontend\actions\InvitesExportToXlsxAction;

/**
 * Invite controller
 *
 * @property mixed $modelClass
 * @property mixed $modelSearchClass
 */
class InviteController extends BaseController implements IModelController
{
	public function getModelClass()
	{
		return Invite::className();
	}

	public function getModelSearchClass()
	{
		return InvitesSearch::className();
	}

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index', 'get-answers-texts', 'save-invite'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	public function actions()
	{
		return [
			'list' => [
				'class' => ListAction::className(),
				'access' => Rights::SHOW_INVITES,
				'searchModelClass' => InvitesSearch::className(),
				'view' => 'list',
				'dataProviderConfig' => [
					'sort' => [
						'attributes' => [
							'id',
							'status',
							'fio',
							'phone',
							'email',
							'sex',
							'age',
							'family',
							'children',
							'repair_status',
							'repair_when_finish',
							'repair_object_other',
							'have_cottage',
							'plan_cottage_works',
							'who_worker',
							'who_chooser',
							'who_buyer',
							'shop_name',
							'city_id' => [
								'asc' => ['city.name' => SORT_ASC],
								'desc' => ['city.name' => SORT_DESC],
							],
							'city_other',
							'distance',
							'money',
						],
						'defaultOrder' => [
							'id' => SORT_DESC
						]
					],
					'pagination' => [
						'defaultPageSize' => 15,
						'pageSize' => 15,
					],
				]
			],
			'view' => [
				'class' => ViewAction::className(),
				'access' => Rights::SHOW_INVITES,
				'returnUrl' => 'list',
			],
			'delete' => [
				'class' => DeleteAction::className(),
				'access' => Rights::SHOW_INVITES,
				'returnUrl' => 'list',
			],
			'export' => [
				'class' => InvitesExportToXlsxAction::className(),
				'access' => Rights::SHOW_INVITES,
			],
		];
	}

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

	/**
	 * @return array
	 * @throws Exception
	 */
    public function actionGetAnswersTexts()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

		return [
			'sex' => $this->getIdValueArray(Invite::$L_SEX),
			'city' => $this->getIdValueArray(City::getList()),
			'family' => $this->getIdValueArray(Invite::$L_FAMILY),
			'children' => $this->getIdValueArray(Invite::$L_HAVE_CHILDREN),
			'repairStatus' => $this->getIdValueArray(Invite::$L_REPAIR_STATUS),
			'repairWhenFinish' => $this->getIdValueArray(Invite::$L_REPAIR_WHEN_FINISH),
			'repairObject' => $this->getIdValueArray(ObjectRepair::getList()),
			'haveCottage' => $this->getIdValueArray(Invite::$L_HAVE_COTTAGE),
			'planCottageWorks' => $this->getIdValueArray(Invite::$L_PLAN_COTTAGE_WORKS),
			'whoWorker' => $this->getIdValueArray(Invite::$L_WHO_WORKER),
			'whoChooser' => $this->getIdValueArray(Invite::$L_WHO_CHOOSER),
			'whoBuyer' => $this->getIdValueArray(Invite::$L_WHO_BUYER),
			'money' => $this->getIdValueArray(Invite::$L_MONEY),
			'distance' => $this->getIdValueArray(Invite::$L_DISTANCE),
			'typeOfRepair' => $this->getIdValueArray(Invite::$L_TYPE_OF_REPAIR),
		];
    }

    public function actionSaveInvite()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$post = Yii::$app->request->post();
		if (is_array($post) && isset($post['data']) && is_array($d = Json::decode($post['data'])) && (count($d) > 0)) {
			/**@var Connection $db*/
			$db = Yii::$app->getDb();
			$tran = $db->beginTransaction();
			try {
				$invite = new Invite([
					'session_id' => $d['SessionID'],
					'sex' => ($d['sex'] === '') ? null : intval($d['sex']),
					'city_id' => ($d['city'] === '') || (intval($d['city']) === 0) ? null : intval($d['city']),
					'city_other' => ($d['cityOther'] === '') ? null : strval($d['cityOther']),
					'age' => ($d['age'] === '') ? null : strval($d['age']),
					'family' => ($d['family'] === '') ? null : intval($d['family']),
					'children' => ($d['children'] === '') ? null : intval($d['children']),
					'repair_status' => ($d['repairStatus'] === '') ? null : intval($d['repairStatus']),
					'repair_when_finish' => (($d['repairWhenFinish'] === '') || (intval($d['repairWhenFinish']) === 0)) ? null : intval($d['repairWhenFinish']),
					'repair_object_other' => ($d['repairObjectOther'] === '') ? null : strval($d['repairObjectOther']),
					'have_cottage' => ($d['haveCottage'] === '') ? null : intval($d['haveCottage']),
					'plan_cottage_works' => ($d['planCottageWorks'] === '') ? null : intval($d['planCottageWorks']),
					'who_worker' => ($d['whoWorker'] === '') ? null : intval($d['whoWorker']),
					'who_chooser' => ($d['whoChooser'] === '') ? null : intval($d['whoChooser']),
					'who_buyer' => ($d['whoBuyer'] === '') ? null : intval($d['whoBuyer']),
					'shop_name' => ($d['shopName'] === '') ? null : strval($d['shopName']),
					'fio' => ($d['fio'] === '') ? null : strval($d['fio']),
					'phone' => ($d['phone'] === '') ? null : strval($d['phone']),
					'email' => ($d['email'] === '') ? null : strval($d['email']),
					'money' => ($d['money'] === '') ? null : intval($d['money']),
					'distance' => ($d['distance'] === '') ? null : intval($d['distance']),
				]);

				if ($invite->save()) {
					$this->insertRelated($invite->id, $d, 'repairObject', '{{%invite_object_repair}}', 'object_repair_id');
					$this->insertRelated($invite->id, $d, 'typeOfRepair', '{{%invite_type_repair}}', 'type_repair_id');
				}

				$tran->commit();
				return ['result' => 'ok'];
			} catch (Exception $e) {
				try {
					$tran->rollBack();
				}
				catch (\yii\db\Exception $e) {
				}

				return [
					'result' => 'error',
					'msg' => $e->getMessage(),
					'code' => $e->getCode(),
				];
			}
		}

		return ['result' => 'error'];
	}

	/**
	 * @return string
	 * @throws ForbiddenHttpException
	 * @throws NotFoundHttpException
	 */
	public function actionUpdateEmail()
	{
		// Проверка доступа
		if (!Yii::$app->user->can(Rights::SHOW_INVITES, []))
			throw new ForbiddenHttpException($this->noAccessMessage);

		$model = new InviteChangeEmailForm();

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				/**@var Invite $invite */
				if (($invite = Invite::findOne($model->invite_id)) === null) {
					throw new NotFoundHttpException("Invite not found");
				}

				$invite->email = $model->email;
				if ($invite->save(true, ['email'])) {
					$model->saved = true;
				}
			}
		}

		return $this->render('__modal_change_email', ['model' => $model]);
	}

	/**
	 * @return string
	 * @throws ForbiddenHttpException
	 * @throws NotFoundHttpException
	 */
	public function actionUpdateStatus()
	{
		// Проверка доступа
		if (!Yii::$app->user->can(Rights::SHOW_INVITES, []))
			throw new ForbiddenHttpException($this->noAccessMessage);

		$model = new InviteChangeStatusForm();

		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				/**@var Invite $invite */
				if (($invite = Invite::findOne($model->invite_id)) === null) {
					throw new NotFoundHttpException("Invite not found");
				}

				$invite->status = $model->status;
				if ($invite->save(true, ['status'])) {
					$model->saved = true;
				}
			}
		}

		return $this->render('__modal_change_status', ['model' => $model]);
	}

	/**
	 * @param integer $id
	 * @return array
	 * @throws ForbiddenHttpException
	 * @throws NotFoundHttpException
	 * @throws \ReflectionException
	 */
	public function actionSendRegistrationEmail($id)
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		/**@var Invite $model */
		$model = (new ReflectionClass($this->getModelClass()))->newInstance();
		if ((($model = $model::findOne($id)) === null)) {
			throw new NotFoundHttpException("{$this->getModelClass()} not found");
		}

		// Проверка доступа
		if (!Yii::$app->user->can(Rights::SHOW_INVITES, [])) {
			throw new ForbiddenHttpException($this->noAccessMessage);
		}

		$model->approveForRegistration();

		return ['result' => 'ok'];
	}

	/**
	 * @param $inviteId
	 * @param $d
	 * @param $property
	 * @param $table
	 * @param $column
	 * @throws \yii\db\Exception
	 */
	private function insertRelated($inviteId, $d, $property, $table, $column)
	{
		if (isset($d[$property]) && !empty($d[$property])) {
			$db = Yii::$app->getDb();

			foreach (explode(';', $d[$property]) as $id) {
				if ((int)$id !== 0) {
					$db->createCommand()->insert($table, ['invite_id' => $inviteId, $column => intval($id)])->execute();
				}
			}
		}
	}

	private function getIdValueArray($data) {
		$result = [];
		foreach ($data as $id => $value) {
			$result[] = ['id' => $id, 'value' => $value];
		}

		return $result;
	}
}
