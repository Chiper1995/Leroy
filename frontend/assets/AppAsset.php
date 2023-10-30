<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.min.css',
    ];
    public $js = [
    ];
    public $depends = [
        'frontend\assets\IE8CompatibilityAsset',
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'xj\bootbox\BootboxAsset',
        'webtoucher\cookie\AssetBundle',
    ];

    public static function overrideSystemConfirm()
    {
        Yii::$app->view->registerJs('
            yii.confirm = function(message, ok, cancel) {
                bootbox.dialog({
                    message: message,
                    title: "Подтверждение",
                    buttons: {
                        success: {
                            label: "Да",
                            className: "btn-primary btn-with-margin-right",
                            callback: function() {!ok || ok();}
                        },
                        cancel: {
                            label: "Отмена",
                            className: "btn-default",
                            callback: function() {!cancel || cancel();}
                        },
                    }
                });
            }
            
            yii.error = function(message, ok) {
            	bootbox.dialog({
                    message: message,
                    title: "Ошибка",
                    buttons: {
                        success: {
                            label: "Ok",
                            className: "btn-primary btn-with-margin-right",
                            callback: function() {!ok || ok();}
                        },
                    }
                });
            }
        ');
    }

    public static function register($view)
    {
        $res = parent::register($view);
        self::overrideSystemConfirm();
        self::addInformMessage();
        return $res;
    }

    public static function addInformMessage()
    {
        if (Yii::$app->user) {
            /* @var User $user*/
            $user = Yii::$app->user->getIdentity();
            if($user) {
                $cities = ArrayHelper::getColumn($user->cities, 'id');

                if ((!isset($_COOKIE['changes-08-2017-inform']) || ($_COOKIE['changes-08-2017-inform'] != '1')) && ($user->created_at <= mktime(0, 0, 0, 8, 15, 2017)) && (time() < mktime(0, 0, 0, 9, 15, 2017))) {
                    Yii::$app->view->registerJsFile('@web/js/banners/changes-08-2017-inform.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
                }

                if ((!isset($_COOKIE['banner-10-2017-inform']) || ($_COOKIE['banner-10-2017-inform'] != '1')) && (time() < mktime(0, 0, 0, 11, 3, 2017))
                    && ($user->role == User::ROLE_FAMILY) && (array_search(9, $cities) !== false || array_search(26, $cities) !== false) // Москва или область
                ) {
                    Yii::$app->view->registerJsFile('@web/js/banners/banner-10-2017-inform.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
                }

                if ((!isset($_COOKIE['banner-12-2017-inform']) || ($_COOKIE['banner-12-2017-inform'] != '1'))
                    && ($user->created_at <= mktime(0, 0, 0, 12, 12, 2017)) && (time() < mktime(0, 0, 0, 1, 12, 2018))
                ) {
                    Yii::$app->view->registerJsFile('@web/js/banners/banner-12-2017-inform.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
                }

                if ((!isset($_COOKIE['banner-2017-12-30-inform']) || ($_COOKIE['banner-2017-12-30-inform'] != '1'))
                    && ($user->role == User::ROLE_FAMILY) && (time() < mktime(0, 0, 0, 01, 10, 2018))
                ) {
                    Yii::$app->view->registerJsFile('@web/js/banners/banner-2017-12-30-inform.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
                }

                if ((!isset($_COOKIE['banner-2018-07-26_changes-info']) || ($_COOKIE['banner-2018-07-26_changes-info'] != '1'))
                    && ($user->role == User::ROLE_FAMILY) && (time() < mktime(0, 0, 0, 8, 13, 2018))
                ) {
                    Yii::$app->view->registerJsFile('@web/js/banners/banner-2018-07-26_changes-info.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
                }

                if ((!isset($_COOKIE['banner-2019-03-15-info']) || ($_COOKIE['banner-2019-03-15-info'] != '1'))
                    && ($user->role == User::ROLE_FAMILY) && (time() < mktime(0, 0, 0, 04, 15, 2019))
                ) {
                    Yii::$app->view->registerJsFile('@web/js/banners/banner-2019-03-15-info.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
                }

				if ((!isset($_COOKIE['banner-covid-19']) || ($_COOKIE['banner-covid-19'] != '1'))
					&& ($user->role !== User::ROLE_FAMILY) && (time() < mktime(0, 0, 0, 05, 01, 2020))
				) {
					Yii::$app->view->registerJsFile('@web/js/banners/banner-covid-19.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
				}

				if ((!isset($_COOKIE['banner-covid-19-family']) || ($_COOKIE['banner-covid-19-family'] != '1'))
					&& ($user->role === User::ROLE_FAMILY) && (time() < mktime(0, 0, 0, 05, 01, 2020))
				) {
					Yii::$app->view->registerJsFile('@web/js/banners/banner-covid-19-family.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
				}

				if ((!isset($_COOKIE['banner-2020-06-15']) || ($_COOKIE['banner-2020-06-15'] != '1'))
					&& ($user->role === User::ROLE_FAMILY) && (time() < mktime(0, 0, 0, 06, 29, 2020))
				) {
					Yii::$app->view->registerJsFile('@web/js/banners/banner-2020-06-15.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
				}

				if ((!isset($_COOKIE['banner-2020-06-30']) || ($_COOKIE['banner-2020-06-30'] != '1'))
					&& ($user->role === User::ROLE_FAMILY) && (time() < mktime(0, 0, 0, 07, 15, 2020))
				) {
					Yii::$app->view->registerJsFile('@web/js/banners/banner-2020-06-30.js', ['position' => View::POS_END, 'depends' => \yii\web\JqueryAsset::className()]);
				}
            }
        }
    }
}
