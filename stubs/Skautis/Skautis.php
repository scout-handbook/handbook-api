<?php

declare(strict_types=1);

namespace Skautis;

/**
 * @SuppressWarnings("PHPMD.CamelCasePropertyName")
 * @SuppressWarnings("PHPMD.UnusedFormalParameter")
 */
class Skautis
{
    use HelperTrait;

    /** @var UserManagement */
    public $UserManagement;

    /** @return User */
    public function getUser() {}

    /**
     * @param array{
     *     skautIS_Token?: string,
     *     skautIS_IDRole?: int,
     *     skautIS_IDUnit?: int,
     *     skautIS_DateLogout?: string
     * } $data
     * @return void
     */
    public function setLoginData($data) {}
}
