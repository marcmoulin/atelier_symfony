<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 */
class Utilisateur
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
    private $login;

    /**
     * @ORM\OneToMany(targetEntity=Sujet::class, mappedBy="auteur")
     */
    private $sujets;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="auteurMessage")
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class, inversedBy="utilisateursInteresses")
     * @ORM\JoinTable(name="produit_suivi")
     */
    private $produitsSuivis;

    public function __construct()
    {
        $this->sujets = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->produitsSuivis = new ArrayCollection();
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
     * @return Collection|Sujet[]
     */
    public function getSujets(): Collection
    {
        return $this->sujets;
    }

    public function addSujet(Sujet $sujet): self
    {
        if (!$this->sujets->contains($sujet)) {
            $this->sujets[] = $sujet;
            $sujet->setAuteur($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujets->removeElement($sujet)) {
            // set the owning side to null (unless already changed)
            if ($sujet->getAuteur() === $this) {
                $sujet->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAuteurMessage($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAuteurMessage() === $this) {
                $message->setAuteurMessage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduitsSuivis(): Collection
    {
        return $this->produitsSuivis;
    }

    public function addProduitsSuivi(Produit $produitsSuivi): self
    {
        if (!$this->produitsSuivis->contains($produitsSuivi)) {
            $this->produitsSuivis[] = $produitsSuivi;
        }

        return $this;
    }

    public function removeProduitsSuivi(Produit $produitsSuivi): self
    {
        $this->produitsSuivis->removeElement($produitsSuivi);

        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->login;
    }

}
