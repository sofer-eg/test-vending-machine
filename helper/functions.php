<?php
function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

if (!function_exists('mb_ucwords')) {
    function mb_ucwords($str)
    {
        return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
    }
}
