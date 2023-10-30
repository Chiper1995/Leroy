<?php

namespace frontend\urlRules;

use common\models\Journal;
use frontend\models\journal\AllJournalSearch;
use ReflectionClass;
use yii\base\Object;
use yii\web\UrlRuleInterface;

class AllJournalUrlRule extends Object implements UrlRuleInterface
{
    const ROUTE = 'journal/all-journals';
    const SEARCH_PARAM = 'status';

    private $statusNames = [
        Journal::STATUS_DRAFT=>'draft',
        Journal::STATUS_ON_CHECK=>'on-check',
        Journal::STATUS_PUBLISHED=>'published',
    ];

    /**
     * Имя поиска, для впихивания фильтрации
     * @var string
     */
    private $searchParamName;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->searchParamName = $this->getSearchParamName(AllJournalSearch::className());
    }

    /**
     * Получаем имя поиска по имени класса
     * @param $class
     * @return string
     */
    private function getSearchParamName($class)
    {
        $reflector = new ReflectionClass($class);
        return $reflector->getShortName();
    }

    public function createUrl($manager, $route, $params)
    {
        if ($route === self::ROUTE) {
            $url = $route;

            // Параметр задан просто
            if (isset($params[self::SEARCH_PARAM]) and ($params[self::SEARCH_PARAM] != null)) {
                if ($params[self::SEARCH_PARAM] > 0) {
                    $url = $route . '/' . $this->statusNames[$params[self::SEARCH_PARAM]];
                    unset($params[self::SEARCH_PARAM]);
                }
            }
            // Параметр задан через параметр поиска
            else if (isset($params[$this->searchParamName][self::SEARCH_PARAM]) and ($params[$this->searchParamName][self::SEARCH_PARAM] != null)) {
                if ($params[$this->searchParamName][self::SEARCH_PARAM] > 0) {
                    $url = $route . '/' . $this->statusNames[$params[$this->searchParamName][self::SEARCH_PARAM]];
                    unset($params[$this->searchParamName][self::SEARCH_PARAM]);
                }
            }
            else {
                unset($params[self::SEARCH_PARAM]);
            }

            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $url .= '?' . $query;
            }

            return $url;
        }
        return false;
    }

    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^'.self::ROUTE.'(/(.*))$%', $pathInfo, $matches)) {
//            Не помню зачем это нужно, но сейчас оно мешает
//            if (isset($_GET[$this->searchParamName]))
//                $_GET[$this->searchParamName] = [];
            $sf = array_flip($this->statusNames);
            if (isset($matches[2])and($matches[2]!='')and(isset($sf[rtrim($matches[2], '/')])))
                $_GET[$this->searchParamName][self::SEARCH_PARAM] = $sf[rtrim($matches[2], '/')];

            return [self::ROUTE, []];
        }
        return false;
    }
}