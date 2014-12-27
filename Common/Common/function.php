<?PHP
/**
 * 递归过滤数组
 *
 * @param array $input
 * @return array
 */
function array_filter_recursive($input) {
    foreach ($input as &$value) {
        if (is_array($value)) {
            $value = array_filter_recursive($value);
        }
    }

    return array_filter($input);
}

/**
 * 截断字符串
 *
 * @param mixed $str
 * @param int $length
 * @access public
 * @return void
 */
function str_cut_off($str, $length = 10, $sufix = '...') {
    if (mb_strlen($str) > $length) {
        return mb_substr($str, 0, $length) . $sufix;
    } else {
        return $str;
    }
}
