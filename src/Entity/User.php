<?php

namespace App\Entity;

use App\Enum\Sex;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, unique: true)]
    private string $name;

    #[ORM\Column]
    private int $age;

    #[ORM\Column(type: Types::STRING, length: 6, enumType: Sex::class)]
    private Sex $sex;

    public function __construct(string $name, int $age, Sex $sex)
    {
        $this->name = $name;
        $this->age = $age;
        $this->sex = $sex;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getSex(): Sex
    {
        return $this->sex;
    }

}
