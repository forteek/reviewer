<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Constant\ProductGroups;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups([
        ProductGroups::INDEX,
        ProductGroups::SHOW,
    ])]
    private ?int $id;

    #[ORM\Column(type: Types::STRING, length:255)]
    #[Constraints\Type(type: Types::STRING)]
    #[Constraints\Length(min:1, max: 255)]
    #[Constraints\NotBlank(allowNull: false, groups: [ProductGroups::CREATE])]
    #[Groups([
        ProductGroups::INDEX,
        ProductGroups::SHOW,
        ProductGroups::CREATE,
        ProductGroups::UPDATE,
    ])]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    #[Constraints\Type(type: Types::STRING)]
    #[Constraints\Length(min:1)]
    #[Constraints\NotBlank(allowNull: false, groups: [ProductGroups::CREATE])]
    #[Groups([
        ProductGroups::SHOW,
        ProductGroups::CREATE,
        ProductGroups::UPDATE,
    ])]
    private string $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}