<?php
## formate date
if (!function_exists('covertDate')) {
    function covertDate($data)
    {
        if (!empty($data)) {
            return date('Y-m-d', strtotime($data));
        }
    }
}

## remove comma from string
if (!function_exists('removeComma')) {
    function removeComma($data)
    {
        if (!empty($data)) {
            return str_replace(',', '', $data);
        }
    }
}

## check webphone template id
if (!function_exists('webPhoneTemp')) {
    function webPhoneTemp($data)
    {
        if ($data == 'Y') {
            return 'webrtc';
        }

        return '';
    }
}

## drop call list
if (!function_exists('dropCallList')) {
    function dropCallList()
    {
        return array('DROP', 'TIMEOT');
    }
}

## realtime agent status
if (!function_exists('realTimeAgentStatus')) {
    function realTimeAgentStatus($status, $callerid, $pause_code)
    {
        $html = '';
        if ($status == 'CLOSER' || $status == 'READY') {
            $html = '<span class="badge bg-info text-dark">READY</span>';
        } else if ($status == 'PAUSED') {
            $html = '<span class="badge bg-warning text-dark">' . $status . '</span>';
        } else if ($status == 'PAUSED' && !empty($pause_code)) {
            $html = '<span class="badge bg-warning text-dark">' . $status . '(' . $pause_code . ')</span>';
        } else if ($status == 'INCALL') {
            $html = '<span class="badge bg-danger">INCALL</span>';
        }

        return $html;
    }
}


## active/inactive status
if (!function_exists('status')) {
    function status($status)
    {
        $html = '';
        if ($status == 'Y') {
            $html = '<span class="badge bg-info text-dark">Active</span>';
        } else {
            $html = '<span class="badge bg-danger">Inactive</span>';
        }

        return $html;
    }
}

## active/inactive status
if (!function_exists('status_01')) {
    function status_01($status)
    {
        $html = '';
        if ($status == '1') {
            $html = '<span class="badge bg-info text-dark">Active</span>';
        } else {
            $html = '<span class="badge bg-danger">Inactive</span>';
        }

        return $html;
    }
}

## get last N number of days
if (!function_exists('getLastNDays')) {
    function getLastNDays($days, $format = 'd/m')
    {
        $m = date("m");
        $de = date("d");
        $y = date("Y");
        $dateArray = array();
        for ($i = 0; $i <= $days - 1; $i++) {
            $dateArray[] = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));
        }
        return array_reverse($dateArray);
    }
}



## data for development
## users list
if (!function_exists('usersList')) {
    function usersList()
    {
        return ['atik', 'hridoy', 'shirin', 'kasfiya', 'FoodAyesha', 'tanha'];
    }
}

## status list
if (!function_exists('statusList')) {
    function statusList()
    {
        return ['DROP', 'SQ', 'TIMEOT', 'DISPO', 'SO', 'AFTHRS', 'HnUp'];
    }
}

## pause code list
if (!function_exists('pauseCodeList')) {
    function pauseCodeList()
    {
        return ['LOGIN', 'Wash', 'Lunch', 'TeaBr', 'TCG', 'Prayer', 'DISMX'];
    }
}
