<?php
/**
 * Интерфейс экшенов
 */

namespace PhpBase\Mvc;


/**
 * Интерфейс экшенов
 */
interface IAction
{
    /**
     * Выполняет действие, возвращает ответ
     *
     * @param Request $request Объект запроса
     * @return Response
     */
    public function run(Request $request);
}

