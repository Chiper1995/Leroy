<?php
namespace frontend\controllers;

use common\components\actions\DeleteAction;
use common\components\actions\UpdateAction;
use common\components\controllers\BaseController;
use common\components\controllers\IModelController;
use common\models\ForumMessage;
use common\models\ForumTheme;
use frontend\actions\forum\ForumListAction;
use frontend\actions\forum\ForumMessagesListAction;
use frontend\actions\forum\ForumMessagesThemeCreateAction;
use frontend\actions\forum\ForumMessagesThemeUpdateAction;
use frontend\actions\forum\ForumThemeCreateAction;
use frontend\models\forum\ForumMessageSearch;
use frontend\models\forum\ForumThemeSearch;
use yii\filters\AccessControl;

class ForumController extends BaseController implements IModelController
{
    public $layout = 'mainEmpty';

    public function getModelClass()
    {
        return ForumTheme::className();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
//                        'allow' => true,
//                        'roles' => ['@'],
						'allow' => false
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => ForumListAction::className(),
                'modelClass' => ForumTheme::className(),
                'searchModelClass' => ForumThemeSearch::className(),
                'view' => 'list',
                //'access' => Rights::SHOW_VISITS,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 20,
                        'defaultPageSize' => 20,
                    ],
                    'sort' => [
                        'attributes' => [
                        ],
                        'defaultOrder' => [
                        ]
                    ]
                ]
            ],

            'create' => [
                'class' => ForumThemeCreateAction::className(),
                //'access' => Rights::SHOW_VISITS,
                'returnUrl' => 'forum/index',
            ],

            'create-message-theme' => [
                'class' => ForumMessagesThemeCreateAction::className(),
                //'access' => Rights::SHOW_VISITS,
                'returnUrl' => 'forum/index',
                'view' => 'createMessagesTheme',
            ],

            'update' => [
                'class' => UpdateAction::className(),
                'returnUrl' => 'forum/index',
                //'access' => Rights::SHOW_VISITS,
            ],

            'update-message-theme' => [
                'class' => ForumMessagesThemeUpdateAction::className(),
                //'access' => Rights::SHOW_VISITS,
                'returnUrl' => 'forum/index',
                'view' => 'updateMessagesTheme',
            ],

            'delete' => [
                'class' => DeleteAction::className(),
                'returnUrl' => 'forum/index',
                //'access' => Rights::SHOW_VISITS,
            ],

            'messages' => [
                'class' => ForumMessagesListAction::className(),
                'modelClass' => ForumMessage::className(),
                'searchModelClass' => ForumMessageSearch::className(),
                'view' => 'messages',
                //'access' => Rights::SHOW_VISITS,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 20,
                        'defaultPageSize' => 20,
                    ],
                    'sort' => [
                        'attributes' => [
                        ],
                        'defaultOrder' => [
                        ]
                    ]
                ]
            ],

        ];
    }

}