<?php
use Carbon\Carbon;

if (!function_exists('activeSegment')) {
    function activeSegment($name, $segment = 2, $class = 'active')
    {
        return request()->segment($segment) == $name ? $class : '';
    }
}

if(!function_exists('dateformat_custom')){
    function dateformat_custom(){
        if(config('settings.date_system') == 0){
            return Carbon::now();
        } else {
            return config('settings.date_system_value');
        }
    }
}
