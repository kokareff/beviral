<?php
/**
 * Фабрика обработчиков запроса
 */

namespace PhpBase\Mvc;

/**
 * Интерфейс фабрики обработчиков
 */
interface IRouter
{
    /**
     * Возвращает объект действия для запроса
     *
     * @param Request $request Объект запроса
     * @return IAction
     */
    public function getAction(Request $request);
}
