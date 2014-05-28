<?php

namespace Zotto\API;

interface ApiInterface {
    public function request($act, $params=[]);
} 