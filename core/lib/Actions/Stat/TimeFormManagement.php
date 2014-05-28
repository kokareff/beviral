<?php


namespace Zotto\Actions\Stat;

use PhpBase\Mvc\Request;

trait TimeFormManagement {
    private function getStartTime(Request $request){
        $fromDate = strtotime($request->get->get('fromDate', date('d.m.Y H:i:s', strtotime('4 hours ago'))));
        return $fromDate;
    }

    private function getEndTime(Request $request){
        $toDate = $request->get->get('toDate', date('d.m.Y H:i:s'));
        return strtotime($toDate);
    }
} 