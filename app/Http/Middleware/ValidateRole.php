<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\UserRole;
use Closure;
use Illuminate\Http\Request;
use Skaut\HandbookAPI\v1_0\Exception\AuthenticationException;
use Skaut\HandbookAPI\v1_0\Exception\RoleException;
use Skaut\HandbookAPI\v1_0\Exception\SkautISException;
use Skautis\Exception as SkautisNativeException;
use Skautis\Skautis;
use Symfony\Component\HttpFoundation\Response;

final class ValidateRole
{
    public function __construct(private Skautis $skautis) {}

    /**
     * @param\Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     *
     * @throws AuthenticationException
     * @throws RoleException
     */
    public function handle(Request $request, Closure $next, string $requiredRole): Response
    {
        $requiredRole = UserRole::from($requiredRole);

        if ($requiredRole === UserRole::Guest) {
            return $next($request);
        }

        if (! $this->skautis->getUser()->isLoggedIn()) {
            throw new AuthenticationException;
        }

        if ($requiredRole !== UserRole::User) {
            $role = UserRole::get($this->skautis->UserManagement->LoginDetail()->ID_Person);

            if (UserRole::compare($role, $requiredRole) < 0) {
                throw new RoleException;
            }
        }

        // TODO Remove!
        try {
            return $next($request);
        } catch (SkautisNativeException $e) {
            throw new SkautISException($e);
        }
    }
}
