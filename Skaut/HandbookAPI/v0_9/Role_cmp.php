<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9;

@_API_EXEC === 1 or die('Restricted access.');

function Role_cmp(Role $first, Role $second) : int
{
    return $first->role <=> $second->role;
}
