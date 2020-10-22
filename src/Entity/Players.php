<?php

namespace App\Entity;

use App\Repository\PlayersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=PlayersRepository::class)
 */
class Players
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="players", orphanRemoval=true,cascade={"persist"})
     */
    private $images;

    /**
     *@Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $poste;

    /**
     * @ORM\Column(type="integer")
     */
    private $taille;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ancienClub;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresseMail;

    /**
     * @ORM\ManyToMany(targetEntity=Annonces::class, mappedBy="players")
     */
    private $annonces;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPlayers($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getPlayers() === $this) {
                $image->setPlayers(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getAncienClub(): ?string
    {
        return $this->ancienClub;
    }

    public function setAncienClub(string $ancienClub): self
    {
        $this->ancienClub = $ancienClub;

        return $this;
    }

    public function getAdresseMail(): ?string
    {
        return $this->adresseMail;
    }

    public function setAdresseMail(string $adresseMail): self
    {
        $this->adresseMail = $adresseMail;

        return $this;
    }

    /**
     * @return Collection|Annonces[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonces $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->addPlayer($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonces $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            $annonce->removePlayer($this);
        }

        return $this;
    }
}
