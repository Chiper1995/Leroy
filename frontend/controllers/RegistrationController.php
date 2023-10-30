<?php
namespace frontend\controllers;

use beastbytes\wizard\WizardBehavior;
use beastbytes\wizard\WizardEvent;
use common\events\AppEvents;
use common\models\City;
use common\models\Invite;
use common\models\ObjectRepair;
use common\models\RoomRepair;
use common\models\User;
use common\models\WorkRepair;
use common\models\UserLocation;
use Yii;
use yii\base\Event;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use common\components\helpers\MapApiHelper;

/**
 * Class RegistrationController
 * @package frontend\controllers
 *
 * @mixin WizardBehavior
 */
class RegistrationController extends Controller
{
	/** @var Invite */
	private $invite;

	private $registration_id_key = 'registration_id';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'wizard' => [
                'class' => WizardBehavior::className(),
                'steps' => ['profile', 'repairObject', 'works'],
                'forwardOnly' => true,
                'events' => [
                    WizardBehavior::EVENT_WIZARD_STEP => [$this, 'registrationWizardStep'],
                    WizardBehavior::EVENT_AFTER_WIZARD => [$this, 'registrationAfterWizard'],
                    WizardBehavior::EVENT_INVALID_STEP => [$this, 'invalidStep']
                ]
            ],
        ];
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function actionIndex($id = null)
    {
    	if ($id === null) {
    		return $this->redirect(['site/index']);
		}
    	else {
    		$invite = Invite::findOne(['session_id' => $id]);
    		if (($invite === null) || ($invite->status === Invite::STATUS_REGISTERED)) {
				return $this->redirect(['site/index']);
			}

			Yii::$app->session->removeAll();
    		Yii::$app->session->set($this->registration_id_key, $invite->id);
			return $this->redirect(['registration/start']);
		}
    }

	/**
	 * @param WizardEvent|null $step
	 * @return mixed
	 */
	public function actionStep($step = null)
	{
		if (($registration_id = Yii::$app->session->get($this->registration_id_key)) === null) {
			return $this->redirect(['site/index']);
		}

		$invite = Invite::findOne($registration_id);
		if ($invite === null) {
			return $this->redirect(['site/index']);
		}

		$this->invite = $invite;

		return $this->step($step);
	}

	/**
	 * Process wizard steps.
	 * The event handler must set $event->handled=true for the wizard to continue
	 * @param WizardEvent $event The event
	 * @return array
	 * @throws ForbiddenHttpException
	 */
    public function registrationWizardStep($event)
    {
		if ($this->invite === null) {
			throw new ForbiddenHttpException();
		}

        if (empty($event->stepData)) {
        	/** @var Model $model */
            $modelName = 'frontend\\models\\registration\\' . ucfirst($event->step);
            $model = new $modelName();

            switch ($event->step) {
				case 'profile':
					$model->load([
						'email' => $this->invite->email,
						'fio' => $this->invite->fio,
						'phone' => $this->invite->phone,
						'city_id' => $this->invite->city_id,
					], '');
					break;
				case 'repairObject':
					$model->load([
						'object_repair_list' => $this->invite->getRepairObject()->select(['id'])->column()
					], '');
					break;
			}
        } else {
            $model = $event->stepData;
        }

        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax && $model->load(Yii::$app->request->post())) {
            $event->data = $model;
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            $event->data = $model;
            $event->handled = true;
        } else {
            $event->data = $this->render($event->step, compact('event', 'model'));
        }

        return [];
    }

	/**
	 * @param $event
	 * @return Response
	 * @throws \yii\db\Exception
	 * @throws ForbiddenHttpException
	 */
	public function registrationAfterWizard($event)
	{
		if (is_string($event->step)) {
			return $this->goHome();
		}
		elseif ($event->step === null) {
			return $this->goHome();
		}
		elseif ($event->step) {
			// Сохраняем пользователя
			if ($this->saveUser($event)) {
				$this->resetWizard();

				return $this->redirect(['registration/complete']);
			}
		}
		else {
			return $this->goHome();
		}
	}

    /**
     * @param WizardEvent $event The event
     */
    public function invalidStep($event)
    {
        $event->data = $this->goHome();
        $event->continue = false;
    }

    public function actionComplete()
    {
        return $this->render('complete');
    }

    /**
     * @param WizardEvent $event
     * @return bool
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    private function saveUser($event)
    {
		if ($this->invite === null) {
			throw new ForbiddenHttpException();
		}

        $transaction = User::getDb()->beginTransaction();
        try {
            $user = new User();
            $user->invite_id = $this->invite->id;
            $user->username = $event->stepData['profile'][0]['username'];
            $user->email = $event->stepData['profile'][0]['email'];
            $user->password = $event->stepData['profile'][0]['password'];
            $user->fio = $event->stepData['profile'][0]['fio'];
            $user->phone = $event->stepData['profile'][0]['phone'];
            $user->address = $event->stepData['profile'][0]['address'];
            $user->family_name = $event->stepData['profile'][0]['family_name'];

            $user->generateAuthKey();

            if ($user->save()) {
                /**@var City $city */
                $city = City::findOne($event->stepData['profile'][0]['city_id']);
                $user->link('cities', $city);

                $coords = MapApiHelper::getCoords($user->address);
                $userLocation = new UserLocation();
                $userLocation->user_id = $user->id;
                $userLocation->adress = $user->address;
                $userLocation->is_home_adress = true;
                $userLocation->latitude = $coords[0];
                $userLocation->longitude = $coords[1];
                $userLocation->city_id = $city->id;
                $userLocation->save();


                foreach ($event->stepData['repairObject'][0]['object_repair_list'] as $objectId) {
                    /**@var ObjectRepair $objectRepair */
                    $objectRepair = ObjectRepair::findOne($objectId);
                    $user->link('repairObjects', $objectRepair);
                }

                foreach ($event->stepData['repairObject'][0]['room_repair_list'] as $roomId) {
                    /**@var RoomRepair $roomRepair */
                    $roomRepair = RoomRepair::findOne($roomId);
                    $user->link('repairRooms', $roomRepair);
                }

                foreach ($event->stepData['works'][0]['work_repair_list'] as $workId) {
                    /**@var WorkRepair $workRepair */
                    $workRepair = WorkRepair::findOne($workId);
                    $user->link('repairWorks', $workRepair);
                }

				$user->activate();

                $this->invite->status = Invite::STATUS_REGISTERED;
				$this->invite->save(true, ['status']);

                // Вызываем событие
                Yii::$app->trigger(AppEvents::EVENT_NEW_USER_REGISTER, new Event(['sender' => $user]));
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
