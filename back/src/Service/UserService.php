<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserService
{

    public function getUserFromRequest(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');
        if (null === $authorizationHeader) {
            return null;
        }
        // string: Bearer $token
        $token = strtolower($authorizationHeader);
        return $this->entityManager->getRepository(User::class)->findOneByTokenUser($token);
    }

}