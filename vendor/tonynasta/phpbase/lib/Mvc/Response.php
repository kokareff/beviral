<?php
/**
 * Файл класса ответа
 */

namespace PhpBase\Mvc;

/**
 * Класс ответа
 */
class Response
{
    /**
     * Код ответа
     *
     * @var int
     */
    protected $_status = 200;

    /**
     * Список известных кодов ответа
     *
     * @var array
     */
    protected static $_statusList = [
        200 => 'OK',
        303 => 'See Other',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error'
    ];

    /**
     * Тело ответа
     *
     * @var string
     */
    protected $_body = '';

    /**
     * Заголовки ответа
     *
     * @var array
     */
    protected $_headers = [];

    /**
     * Куки
     *
     * @var array
     */
    protected $_cookies = [];

    /**
     * Устанавливает тело ответа
     *
     * @param string $content Содержимое
     * @return void
     */
    public function setBody($content)
    {
        $this->_body = $content;
    }


    /**
     * Устанавливает код ответа
     *
     * @param int $code Код
     * @return void
     */
    public function setStatus($code)
    {
        if (isset(self::$_statusList[$code])) {
            $this->_status = $code;
        }
    }


    /**
     * Устанавливает заголовок ответа
     *
     * @param string $name Название
     * @param string $value Значение
     * @return void
     */
    public function setHeader($name, $value)
    {
        $this->_headers[$name] = $value;
    }


    /**
     * Устанавливает куки
     *
     * @param string $name Название
     * @param string $value Значение
     * @param int $expire Время жизни (абсолютное)
     * @return void
     */
    public function setCookie($name, $value, $expire = 0)
    {
        $this->_cookies[$name] = [$value, $expire];
    }

    /**
     * Выводит все данные
     *
     * @return void
     */
    public function send()
    {
        header(
            sprintf(
                'HTTP/1.1 %s %s',
                $this->_status,
                self::$_statusList[$this->_status]
            )
        );

        foreach ($this->_headers as $header => $value) {
            header($header . ': ' . $value);
        }

        foreach ($this->_cookies as $name => $value) {
            setcookie($name, $value[0], $value[1], '/');
        }

        header('Content-Length: ' . strlen($this->_body));
        echo $this->_body;
    }
}
