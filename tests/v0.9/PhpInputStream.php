<?php declare(strict_types=1);
namespace v0_9;

class PhpInputStream
{
    public static function register(array $data) : void
    {
        stream_wrapper_unregister("php");
        stream_wrapper_register("php", '\v0_9\PhpInputStream');
        self::$data = http_build_query($data);
    }

    public static function unregister() : void
    {
        stream_wrapper_restore("php");
    }

    protected static $data = '';
    protected $position = 0;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function stream_open(string $path, string $mode, int $options, &$opened_path) : bool
    {
        return $path === 'php://input';
    }

    public function stream_stat() : array // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    {
        return [];
    }

    public function stream_read(int $count) : string // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    {
        $length = min($count, mb_strlen(self::$data) - $this->position);
        $data = mb_substr(self::$data, $this->position, $length);
        $this->position = $this->position + $length;
        return $data;
    }

    public function stream_eof() : bool // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    {
        return ($this->position >= mb_strlen(self::$data));
    }
}
