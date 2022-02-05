<?php

namespace App\Entity\User;

use App\Entity\AbstractEntity;
use App\Entity\Game\Game;
use App\Enum\UserGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity]
#[ORM\Table(schema: 'user')]
#[UniqueEntity(fields: 'email')]
class User extends AbstractEntity implements PasswordAuthenticatedUserInterface, UserEntityInterface
{
    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    #[Constraints\Type(type: Types::STRING)]
    #[Constraints\Length(min:1, max: 255)]
    #[Constraints\NotBlank(allowNull: false, groups: [UserGroups::CREATE])]
    #[Constraints\Email]
    #[Groups([
        UserGroups::CREATE,
        UserGroups::SHOW,
    ])]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Constraints\Type(type: Types::STRING)]
    #[Constraints\Length(min:1, max: 255)]
    #[Constraints\NotBlank(allowNull: false, groups: [UserGroups::CREATE])]
    #[Groups([
        UserGroups::CREATE,
    ])]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Game::class)]
    private Collection $ownedGames;

    public function __construct()
    {
        $this->ownedGames = new ArrayCollection();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIdentifier()
    {
        return $this->email;
    }

    public function getOwnedGames(): Collection
    {
        return $this->ownedGames;
    }

    public function setOwnedGames(Collection $games): self
    {
        $this->ownedGames = $games;

        return $this;
    }
}
