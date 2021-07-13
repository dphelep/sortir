<?php


namespace App\Class;


use DateTime;

class Filtre
{
    private $campus;

    private $motCle;

    private $dateDebutRecherche;

    private $dateFinRecherche;

    private $sortieOrganisateur;

    private $sortieInscrit;

    private $sortieNonInscrit;

    private $sortiePassee;

    /**
     * @return string
     */
    public function getCampus()
    {
        return $this->campus;
    }

    /**
     * @param string $campus
     */
    public function setCampus($campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return string
     */
    public function getMotCle(): ?string
    {
        return $this->motCle;
    }

    /**
     * @param string $motCle
     */
    public function setMotCle(?string $motCle): void
    {
        $this->motCle = $motCle;
    }

    /**
     * @return DateTime
     */
    public function getDateDebutRecherche(): ?DateTime
    {
        return $this->dateDebutRecherche;
    }

    /**
     * @param DateTime $dateDebutRecherche
     */
    public function setDateDebutRecherche(?DateTime $dateDebutRecherche): void
    {
        $this->dateDebutRecherche = $dateDebutRecherche;
    }

    /**
     * @return DateTime
     */
    public function getDateFinRecherche(): ?DateTime
    {
        return $this->dateFinRecherche;
    }

    /**
     * @param DateTime $dateFinRecherche
     */
    public function setDateFinRecherche(?DateTime $dateFinRecherche): void
    {
        $this->dateFinRecherche = $dateFinRecherche;
    }

    /**
     * @return bool
     */
    public function isSortieOrganisateur(): bool
    {
        return $this->sortieOrganisateur;
    }

    /**
     * @param bool $sortieOrganisateur
     */
    public function setSortieOrganisateur(bool $sortieOrganisateur): void
    {
        $this->sortieOrganisateur = $sortieOrganisateur;
    }

    /**
     * @return bool
     */
    public function isSortieInscrit(): bool
    {
        return $this->sortieInscrit;
    }

    /**
     * @param bool $sortieInscrit
     */
    public function setSortieInscrit(bool $sortieInscrit): void
    {
        $this->sortieInscrit = $sortieInscrit;
    }

    /**
     * @return bool
     */
    public function isSortieNonInscrit(): bool
    {
        return $this->sortieNonInscrit;
    }

    /**
     * @param bool $sortieNonInscrit
     */
    public function setSortieNonInscrit(bool $sortieNonInscrit): void
    {
        $this->sortieNonInscrit = $sortieNonInscrit;
    }

    /**
     * @return bool
     */
    public function isSortiePassee(): bool
    {
        return $this->sortiePassee;
    }

    /**
     * @param bool $sortiePassee
     */
    public function setSortiePassee(bool $sortiePassee): void
    {
        $this->sortiePassee = $sortiePassee;
    }

}
