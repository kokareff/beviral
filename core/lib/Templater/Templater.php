<?php

namespace Zotto\Templater;

interface Templater {
    function render($template, $data);
    function prepare();
} 