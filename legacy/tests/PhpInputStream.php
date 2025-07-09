<?php

declare(strict_types=1);

namespace TestUtils;

class PhpInputStream
{
    public static function register(array $data): void
    {
        stream_wrapper_unregister('php');
        stream_wrapper_register('php', '\TestUtils\PhpInputStream');
        self::$data = http_build_query($data);
    }

    public static function unregister(): void
    {
        stream_wrapper_restore('php');
    }

    protected static $data = '';

    protected $position = 0;

    /**
     * @SuppressWarnings("PHPMD.CamelCaseParameterName")
     * @SuppressWarnings("PHPMD.CamelCaseMethodName")
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function stream_open(string $path, string $mode, int $options, &$opened_path): bool
    {
        return $path === 'php://input';
    }

    /** @SuppressWarnings("PHPMD.CamelCaseMethodName") */
    public function stream_stat(): array // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    {
        return [];
    }

    /** @SuppressWarnings("PHPMD.CamelCaseMethodName") */
    public function stream_read(int $count): string // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    {
        $length = min($count, mb_strlen(self::$data) - $this->position);
        $data = mb_substr(self::$data, $this->position, $length);
        $this->position += $length;

        return $data;
    }

    /** @SuppressWarnings("PHPMD.CamelCaseMethodName") */
    public function stream_eof(): bool // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    {
        return $this->position >= mb_strlen(self::$data);
    }
}
