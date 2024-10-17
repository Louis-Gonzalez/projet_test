<?php 

declare(strict_types=1); // c'est pour la legacy code pour forcer le typage des données pour version antérieur

namespace App\Fixtures\Providers;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class HashPasswordProvider {

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        
    }

    public function hashPassword(string $plainPassword): string {
        return $this->passwordHasher->hashPassword(new User(), $plainPassword);
    }
}