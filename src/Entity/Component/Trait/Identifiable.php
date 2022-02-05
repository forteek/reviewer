<?php

namespace App\Entity\Component\Trait;

use App\Enum\BaseGroups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Identifiable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups([BaseGroups::DEFAULT])]
    private readonly int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
