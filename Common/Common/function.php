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