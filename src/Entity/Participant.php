<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"pseudo", "mail"})
 * @ORM\Entity(repositoryClass=ParticipantRepository::class)
 */
class Participant implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Email(message="L'adresse saisie ('{{ value }}' n'est pas une adresse mail valide")
     */
    private $mail;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $motPasse;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Length(
     *     max=50,
     *     maxMessage="Ce champ ne peut pas contenir plus de 50 caracères"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Length(
     *     max=50,
     *     maxMessage="Ce champ ne peut pas contenir plus de 50 caracères"
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Assert\Length(
     *     max=50,
     *     maxMessage="Ce champ ne peut pas contenir plus de 50 caracères"
     * )
     */
    private $pseudo;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="participant")
     * @ORM\JoinColumn(nullable=false)

     */
    private $campus;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $sortiesOganisees;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participants")
     */
    private $sortiesParticipees;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    public function __construct()
    {
        $this->sortiesOganisees = new ArrayCollection();
        $this->sortiesParticipees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->mail;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->motPasse;
    }

    public function setPassword(string $motPasse): self
    {
        $this->motPasse = $motPasse;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(?bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesOganisees(): Collection
    {
        return $this->sortiesOganisees;
    }

    public function addSortiesOganisee(Sortie $sortiesOganisee): self
    {
        if (!$this->sortiesOganisees->contains($sortiesOganisee)) {
            $this->sortiesOganisees[] = $sortiesOganisee;
            $sortiesOganisee->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesOganisee(Sortie $sortiesOganisee): self
    {
        if ($this->sortiesOganisees->removeElement($sortiesOganisee)) {
            // set the owning side to null (unless already changed)
            if ($sortiesOganisee->getOrganisateur() === $this) {
                $sortiesOganisee->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesParticipees(): Collection
    {
        return $this->sortiesParticipees;
    }

    public function addSortiesParticipee(Sortie $sortiesParticipee): self
    {
        if (!$this->sortiesParticipees->contains($sortiesParticipee)) {
            $this->sortiesParticipees[] = $sortiesParticipee;
            $sortiesParticipee->addParticipant($this);
        }

        return $this;
    }

    public function removeSortiesParticipee(Sortie $sortiesParticipee): self
    {
        if ($this->sortiesParticipees->removeElement($sortiesParticipee)) {
            $sortiesParticipee->removeParticipant($this);
        }

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

}
