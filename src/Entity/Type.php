<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=RealEstate::class, mappedBy="type", orphanRemoval=true)
     */
    private $realEstates;

    public function __construct()
    {
        $this->realEstates = new ArrayCollection();
    }

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

    /**
     * @return Collection|RealEstate[]
     */
    public function getRealEstates(): Collection
    {
        return $this->realEstates;
    }

    public function addRealEstate(RealEstate $realEstate): self
    {
        if (!$this->realEstates->contains($realEstate)) {
            $this->realEstates[] = $realEstate;
            $realEstate->setType($this);
        }

        return $this;
    }

    public function removeRealEstate(RealEstate $realEstate): self
    {
        if ($this->realEstates->removeElement($realEstate)) {
            // set the owning side to null (unless already changed)
            if ($realEstate->getType() === $this) {
                $realEstate->setType(null);
            }
        }

        return $this;
    }

    /**
     * Cette méthode d'utiliser l'objet comme une chaine
     */
    public function __toString()
    {
        return $this->name;
    }
}
