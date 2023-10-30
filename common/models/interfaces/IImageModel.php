<?php
namespace common\models\interfaces;

use common\components\PhotoThumbBehavior;

/**
 * Interface IImageModel
 * @package common\models\interfaces
 *
 * @mixin PhotoThumbBehavior
 */
interface IImageModel
{
    static public function getPath();

    static public function getTempPath();

    static public function getAllowedExtensions();

    static public function getMaxSize();

    static public function getMaxWidth();

    static public function getMaxHeight();

    static public function getTempUrlPath();

    static public function getUrlPath();
}