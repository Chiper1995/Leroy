<?php
namespace common\components;

use yii\base\NotSupportedException;

/**
 * Реализация для русского языка в части множественного числа, для хостинга без расширения intl
 *
 * Class MessageFormatter
 * @package common\components
 */
class MessageFormatter extends \yii\i18n\MessageFormatter
{
    private $expressions = [
                'one' => 'fmod(n,10)==1&&fmod(n,100)!=11',
                'few' => '(fmod(n,10)>=2&&fmod(n,10)<=4&&fmod(fmod(n,10),1)==0)&&(fmod(n,100)<12||fmod(n,100)>14)',
                'many' => 'fmod(n,10)==0||(fmod(n,10)>=5&&fmod(n,10)<=9&&fmod(fmod(n,10),1)==0)||(fmod(n,100)>=11&&fmod(n,100)<=14&&fmod(fmod(n,100),1)==0)',
                'other' => 'true',
            ];

    private $_errorCode = 0;
    private $_errorMessage = '';


    /**
     * Get the error code from the last operation
     * @link http://php.net/manual/en/messageformatter.geterrorcode.php
     * @return string Code of the last error.
     */
    public function getErrorCode()
    {
        return $this->_errorCode;
    }

    /**
     * Get the error text from the last operation
     * @link http://php.net/manual/en/messageformatter.geterrormessage.php
     * @return string Description of the last error.
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    protected function fallbackFormat($pattern, $args, $locale)
    {
        if (($tokens = self::tokenizePattern($pattern)) === false) {
            $this->_errorCode = -1;
            $this->_errorMessage = "Message pattern is invalid.";

            return false;
        }
        foreach ($tokens as $i => $token) {
            if (is_array($token)) {
                if (($tokens[$i] = $this->parseToken($token, $args, $locale)) === false) {
                    $this->_errorCode = -1;
                    $this->_errorMessage = "Message pattern is invalid.";

                    return false;
                }
            }
        }

        return implode('', $tokens);
    }

    /**
     * Tokenizes a pattern by separating normal text from replaceable patterns
     * @param string $pattern patter to tokenize
     * @return array|boolean array of tokens or false on failure
     */
    private static function tokenizePattern($pattern)
    {
        $depth = 1;
        if (($start = $pos = mb_strpos($pattern, '{')) === false) {
            return [$pattern];
        }
        $tokens = [mb_substr($pattern, 0, $pos)];
        while (true) {
            $open = mb_strpos($pattern, '{', $pos + 1);
            $close = mb_strpos($pattern, '}', $pos + 1);
            if ($open === false && $close === false) {
                break;
            }
            if ($open === false) {
                $open = mb_strlen($pattern);
            }
            if ($close > $open) {
                $depth++;
                $pos = $open;
            } else {
                $depth--;
                $pos = $close;
            }
            if ($depth == 0) {
                $tokens[] = explode(',', mb_substr($pattern, $start + 1, $pos - $start - 1), 3);
                $start = $pos + 1;
                $tokens[] = mb_substr($pattern, $start, $open - $start);
                $start = $open;
            }
        }
        if ($depth != 0) {
            return false;
        }

        return $tokens;
    }

    /**
     * Parses a token
     * @param array $token the token to parse
     * @param array $args arguments to replace
     * @param string $locale the locale
     * @return bool|string parsed token or false on failure
     * @throws \yii\base\NotSupportedException when unsupported formatting is used.
     */
    private function parseToken($token, $args, $locale)
    {
        // parsing pattern based on ICU grammar:
        // http://icu-project.org/apiref/icu4c/classMessageFormat.html#details

        $param = trim($token[0]);
        if (isset($args[$param])) {
            $arg = $args[$param];
        } else {
            return '{' . implode(',', $token) . '}';
        }
        $type = isset($token[1]) ? trim($token[1]) : 'none';
        switch ($type) {
            case 'date':
            case 'time':
            case 'spellout':
            case 'ordinal':
            case 'duration':
            case 'choice':
            case 'selectordinal':
                throw new NotSupportedException("Message format '$type' is not supported. You have to install PHP intl extension to use this feature.");
            case 'number':
                if (is_int($arg) && (!isset($token[2]) || trim($token[2]) == 'integer')) {
                    return $arg;
                }
                throw new NotSupportedException("Message format 'number' is only supported for integer values. You have to install PHP intl extension to use this feature.");
            case 'none':
                return $arg;
            case 'select':
                /* http://icu-project.org/apiref/icu4c/classicu_1_1SelectFormat.html
                selectStyle = (selector '{' message '}')+
                */
                if (!isset($token[2])) {
                    return false;
                }
                $select = self::tokenizePattern($token[2]);
                $c = count($select);
                $message = false;
                for ($i = 0; $i + 1 < $c; $i++) {
                    if (is_array($select[$i]) || !is_array($select[$i + 1])) {
                        return false;
                    }
                    $selector = trim($select[$i++]);
                    if ($message === false && $selector == 'other' || $selector == $arg) {
                        $message = implode(',', $select[$i]);
                    }
                }
                if ($message !== false) {
                    return $this->fallbackFormat($message, $args, $locale);
                }
                break;
            case 'plural':
                /* http://icu-project.org/apiref/icu4c/classicu_1_1PluralFormat.html
                pluralStyle = [offsetValue] (selector '{' message '}')+
                offsetValue = "offset:" number
                selector = explicitValue | keyword
                explicitValue = '=' number  // adjacent, no white space in between
                keyword = [^[[:Pattern_Syntax:][:Pattern_White_Space:]]]+
                message: see MessageFormat
                */
                if (!isset($token[2])) {
                    return false;
                }
                $plural = self::tokenizePattern($token[2]);
                $c = count($plural);
                $message = false;
                $offset = 0;
                for ($i = 0; $i + 1 < $c; $i++) {
                    if (is_array($plural[$i]) || !is_array($plural[$i + 1])) {
                        return false;
                    }
                    $selector = trim($plural[$i++]);

                    if ($i == 1 && strncmp($selector, 'offset:', 7) === 0) {
                        $offset = (int) trim(mb_substr($selector, 7, ($pos = mb_strpos(str_replace(["\n", "\r", "\t"], ' ', $selector), ' ', 7)) - 7));
                        $selector = trim(mb_substr($selector, $pos + 1));
                    }

                    if ($message === false) {
                        if (($selector[0] == '=' && (int) mb_substr($selector, 1) == $arg)
                            || ((isset($this->expressions[$selector]))and(self::evaluate(str_replace('n','$n',$this->expressions[$selector]), $arg - $offset)))
                        ) {
                            $message = implode(',', str_replace('#', $arg - $offset, $plural[$i]));
                        }
                    }
                }
                if ($message !== false) {
                    return $this->fallbackFormat($message, $args, $locale);
                }
                break;
        }

        return false;
    }

    /**
     * Evaluates a PHP expression with the given number value.
     * @param string $expression the PHP expression
     * @param mixed $n the number value
     * @return boolean the expression result
     */
    protected static function evaluate($expression,$n)
    {
        return @eval("return $expression;");
    }
}