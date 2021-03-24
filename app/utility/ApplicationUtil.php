<?php

namespace Phapi\Utility;

class ApplicationUtil{

    public static function getProfilerData(){
        $di = \Phalcon\DI::getDefault();
        $profiler = $di->get('profiler');
        $profiles = $profiler->getProfiles();

        $data = [];
        if(!empty($profiles)) {
            foreach ($profiles as $profile) {
                $data[] = [
                    'query' => $profile->getSQLStatement(),
                    'start_time' => $profile->getInitialTime(),
                    'end_time' => $profile->getFinalTime(),
                    'elapsed_nanoseconds' => $profile->getTotalElapsedSeconds(),
                ];
            }
        }

        return [
            'unix_timestamp' => time(),
            'total_queries' => $profiler->getNumberTotalStatements(),
            'total_nanoseconds' => $profiler->getTotalElapsedSeconds(),
            'queries' => $data,
        ];
    }
}