<?php


namespace frontend\urlRules;


use yii\base\Object;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;
use common\models\Journal;
use frontend\models\journal\MySubscriptionSearch;
use ReflectionClass;

class MyJournalUrlRule extends Object implements UrlRuleInterface
{
    const ROUTE = 'journal/my-subscription';
    const SEARCH_PARAM = 'status';

    /**
     * Список статусов
     * @var array
     */
    private $statusName =  [
        Journal::FAVORITE_POST => 'favorite',
    ];

    /**
     * Имя поиска, для впихивания фильтрации
     * @var string
     */
    private $searchParamName;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->searchParamName = $this->getSearchParamName(MySubscriptionSearch::className());
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
                    $url = $route . '/' . $this->statusName[$params[self::SEARCH_PARAM]];
                    unset($params[self::SEARCH_PARAM]);
                }
            }
            // Параметр задан через параметр поиска
            else if (isset($params[$this->searchParamName][self::SEARCH_PARAM]) and ($params[$this->searchParamName][self::SEARCH_PARAM] != null)) {
                if ($params[$this->searchParamName][self::SEARCH_PARAM] > 0) {
                    $url = $route . '/' . $this->statusName[$params[$this->searchParamName][self::SEARCH_PARAM]];
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

    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|boolean the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^'.self::ROUTE.'(/(.*))$%', $pathInfo, $matches)) {
            if (isset($_GET[$this->searchParamName]))
                $_GET[$this->searchParamName] = [];
            $sf = array_flip($this->statusName);
            if (isset($matches[2])and($matches[2]!='')and(isset($sf[rtrim($matches[2], '/')])))
                $_GET[$this->searchParamName] = [self::SEARCH_PARAM=>$sf[rtrim($matches[2], '/')]];

            return [self::ROUTE, []];
        }
        return false;
    }
}