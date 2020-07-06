<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $idUser;

    /**
     *@var Restaurant
     * @ORM\ManyToOne(targetEntity="App\Entity\Restaurant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idrestaurant", referencedColumnName="id" , onDelete="CASCADE")
     * })
     */
    private $idRestaurant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $traite;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return User
     */
    public function getIdUser(): User
    {
        return $this->idUser;
    }

    /**
     * @param User $idUser
     */
    public function setIdUser(User $idUser): void
    {
        $this->idUser = $idUser;
    }






    /**
     * @return mixed
     */
    public function getTraite()
    {
        return $this->traite;
    }

    /**
     * @param mixed $traite
     */
    public function setTraite($traite): void
    {
        $this->traite = $traite;
    }

    /**
     * @return Restaurant
     */
    public function getIdRestaurant(): Restaurant
    {
        return $this->idRestaurant;
    }

    /**
     * @param Restaurant $idRestaurant
     */
    public function setIdRestaurant(Restaurant $idRestaurant): void
    {
        $this->idRestaurant = $idRestaurant;
    }




}
