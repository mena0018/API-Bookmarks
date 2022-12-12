<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Controller\GetAvatarController;
use App\Controller\GetMeController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            uriTemplate: '/me',
            controller: GetMeController::class,
            openapiContext: [
                'summary' => 'Accès aux informations personnelles',
                'description' => 'La route permet de retourner l id, le login, le nom,
                                  le prénom et le mail de l\'utilisateur connecté',
                'responses' => [
                    '200' => [
                        'description' => 'Succès, les informations personnelles de l\'utilisateur sont retournées',
                    ],
                    '401' => [
                        'description' => 'Accès interdit, l\'utilisateur doit se connecter',
                    ],
                ],
            ],
            paginationEnabled: false,
            normalizationContext: ['groups' => ['get_User', 'get_Me']],
            security: "is_granted('ROLE_USER')"
        ),
        new Get(
            uriTemplate: '/users/{id}/avatar',
            formats: [
                'png' => 'image/png',
            ],
            controller: GetAvatarController::class,
            openapiContext: [
                'summary' => 'Accès aux avatars',
                'description' => "La route permet de retourner l'avatar d'un utilisateur",
                'responses' => [
                    '200' => [
                        'description' => "Succès, l'avatar de l\'utilisateur est retournée",
                    ],
                    '404' => [
                        'description' => "Not Found, l'avatar de l\'utilisateur est introuvable",
                    ],
                ],
                'content' => [
                    'image/png' => [
                        'schema' => [
                            'type' => 'string',
                            'format' => 'binary',
                        ],
                    ],
                ],
            ]
        ),
        new Put(
            denormalizationContext: ['groups' => ['set_User']],
            normalizationContext: ['groups' => ['get_User', 'get_Me']],
            security: "is_granted('ROLE_USER') and object == user"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['set_User']],
            normalizationContext: ['groups' => ['get_User', 'get_Me']],
            security: "is_granted('ROLE_USER') and object == user"
        ),
    ],
    normalizationContext: ['groups' => ['get_User']],
)]
#[UniqueEntity('login')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_User'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Regex(
        pattern: '/^[^<,>,&,"]+$/',
        message: 'Caractère non autorisé',
    )]
    #[Groups(['get_User', 'set_User'])]
    private ?string $login = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    #[Groups(['set_User'])]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    #[Assert\Regex(
        pattern: '/^[^<,>,&,"]+$/',
        message: 'Caractère non autorisé',
    )]
    #[Groups(['get_User', 'set_User'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 40)]
    #[Assert\Regex(
        pattern: '/^[^<,>,&,"]+$/',
        message: 'Caractère non autorisé',
    )]
    #[Groups(['get_User', 'set_User'])]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::BLOB)]
    private $avatar = null;

    #[ORM\Column(length: 100)]
    #[Assert\Email(
        message: 'L\' email {{ value }} n\'est pas valide.',
    )]
    #[Groups(['set_User', 'get_Me'])]
    private ?string $mail = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Rating::class, orphanRemoval: true)]
    private Collection $ratings;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getUser() === $this) {
                $rating->setUser(null);
            }
        }

        return $this;
    }
}
