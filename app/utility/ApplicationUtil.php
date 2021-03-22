<?php

namespace Phapi\Utility;

class ApplicationUtil{

    public static function getProfilerData(){
        $di = \Phalcon\DI::getDefault();
        $profiler = $di->get('profiler');

        $now = date("Y-m-d H:i:s");

        $date = new \DateTime();
        $timestamp = $date->getTimestamp();

        $data = [];
        foreach ($profiler->getProfiles() as $profile) {
            $data[] = [
                'query' => $profile->getSQLStatement(),
                'start_time' => $profile->getInitialTime(),
                'end_time' => $profile->getFinalTime(),
                'elapsed_miliseconds' => $profile->getTotalElapsedSeconds(),
            ];
        }

        return [
            'total_queries' => $profiler->getNumberTotalStatements(),
            'total_seconds' => $profiler->getTotalElapsedSeconds(),
            'queries' => $data,
        ];
    }

}