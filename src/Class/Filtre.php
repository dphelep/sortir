<?php


namespace App\Class;


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
    public function getCampus(): string
    {
        return $this->campus;
    }

    /**
     * @param string $campus
     */
    public function setCampus(string $campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return string
     */
    public function getMotCle(): string
    {
        return $this->motCle;
    }

    /**
     * @param string $motCle
     */
    public function setMotCle(string $motCle): void
    {
        $this->motCle = $motCle;
    }

    /**
     * @return string
     */
    public function getDateDebutRecherche(): string
    {
        return $this->dateDebutRecherche;
    }

    /**
     * @param string $dateDebutRecherche
     */
    public function setDateDebutRecherche(string $dateDebutRecherche): void
    {
        $this->dateDebutRecherche = $dateDebutRecherche;
    }

    /**
     * @return string
     */
    public function getDateFinRecherche(): string
    {
        return $this->dateFinRecherche;
    }

    /**
     * @param string $dateFinRecherche
     */
    public function setDateFinRecherche(string $dateFinRecherche): void
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
