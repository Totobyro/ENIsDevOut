<?php

namespace App\Entity;

use App\Repository\FiltreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FiltreRepository::class)
 */
class Filtre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Campus::class, cascade={"persist", "remove"})
     */
    private $campus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $recherche;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sortieOrganisateur;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sortieInscrit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sortieNonInscrit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sortiePasse;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRecherche(): ?string
    {
        return $this->recherche;
    }

    public function setRecherche(?string $recherche): self
    {
        $this->recherche = $recherche;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getSortieOrganisateur(): ?bool
    {
        return $this->sortieOrganisateur;
    }

    public function setSortieOrganisateur(?bool $sortieOrganisateur): self
    {
        $this->sortieOrganisateur = $sortieOrganisateur;

        return $this;
    }

    public function getSortieInscrit(): ?bool
    {
        return $this->sortieInscrit;
    }

    public function setSortieInscrit(?bool $sortieInscrit): self
    {
        $this->sortieInscrit = $sortieInscrit;

        return $this;
    }

    public function getSortieNonInscrit(): ?bool
    {
        return $this->sortieNonInscrit;
    }

    public function setSortieNonInscrit(?bool $sortieNonInscrit): self
    {
        $this->sortieNonInscrit = $sortieNonInscrit;

        return $this;
    }

    public function getSortiePasse(): ?bool
    {
        return $this->sortiePasse;
    }

    public function setSortiePasse(?bool $sortiePasse): self
    {
        $this->sortiePasse = $sortiePasse;

        return $this;
    }
}
