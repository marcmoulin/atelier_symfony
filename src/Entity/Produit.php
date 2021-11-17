<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\OneToOne(targetEntity=Forum::class, cascade={"persist", "remove"})
     */
    private $forum;

    /**
     * @ORM\ManyToMany(targetEntity=Utilisateur::class, mappedBy="produitsSuivis")
     */
    private $utilisateursInteresses;

    public function __construct()
    {
        $this->utilisateursInteresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getForum(): ?Forum
    {
        return $this->forum;
    }

    public function setForum(?Forum $forum): self
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateursInteresses(): Collection
    {
        return $this->utilisateursInteresses;
    }

    public function addUtilisateursInteress(Utilisateur $utilisateursInteress): self
    {
        if (!$this->utilisateursInteresses->contains($utilisateursInteress)) {
            $this->utilisateursInteresses[] = $utilisateursInteress;
            $utilisateursInteress->addProduitsSuivi($this);
        }

        return $this;
    }

    public function removeUtilisateursInteress(Utilisateur $utilisateursInteress): self
    {
        if ($this->utilisateursInteresses->removeElement($utilisateursInteress)) {
            $utilisateursInteress->removeProduitsSuivi($this);
        }

        return $this;
    }
}
