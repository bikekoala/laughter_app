<?PHP
namespace Common\Addons;

/**
 * AES加密解密类
 *
 * @author popfeng <popfeng@yeah.net>
 */
class AES
{
    /**
     * 模式
     */
    const MODE = MCRYPT_MODE_ECB;

    /**
     * 加密
     *
     * @param string $str
     * @param string $key
     * @return string
     */
    public function encrypt($str, $key)
    {
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);

        return mcrypt_encrypt(MCRYPT_DES, $key, $str, self::MODE);
    }

    /**
     * 解密
     *
     * @param string $str
     * @param string $key
     * @return string
     */
    public function decrypt($str, $key)
    {
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, self::MODE);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);

        return substr($str, 0, strlen($str) - $pad);
    }
}
