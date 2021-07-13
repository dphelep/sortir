<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Class\Filtre;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    public function findSorties(Filtre $filtre) {

        $dateDuJour = new DateTime('now');

        /* Recherche par mot-clé */
        $query = $this->createQueryBuilder('s')
            ->where('s.nom LIKE :motCle')
            ->setParameter('motCle', "%{$filtre->getMotCle()}%");

        /* Recherche selon campus */
        if (!is_null($filtre->getCampus())) {
            $query->andWhere('IDENTITY(s.siteOrganisateur) LIKE :siteOrganisateur')
                ->setParameter('siteOrganisateur', $filtre->getCampus());
        }

        /* Entre dateDebut et dateFin (date du jour par défaut) */
        if (!is_null($filtre->getDateDebutRecherche()) && (!is_null($filtre->getDateFinRecherche()))) {
            $query->andWhere('s.dateHeureDebut BETWEEN ?1 AND ?2')
                ->setParameter(1, $filtre->getDateDebutRecherche())
                ->setParameter(2, $filtre->getDateFinRecherche());
        }

        if (!is_null($filtre->getDateDebutRecherche()) && (is_null($filtre->getDateFinRecherche()))) {
            $query->andWhere('s.dateHeureDebut BETWEEN ?1 AND ?2')
                ->setParameter(1, $filtre->getDateDebutRecherche())
                ->setParameter(2, $dateDuJour);
        }

        /* Recherche si organisateur */
        if ($filtre->isSortieOrganisateur()) {
            $query->andWhere('IDENTITY(s.organisateur) LIKE :organisateur')
                ->setParameter('organisateur', $this->security->getUser());
        }

        /* Recherche si inscrit */
        if ($filtre->isSortieInscrit()) {
            $query->innerJoin('s.participants', 'p', 'WITH', 'p.id = :userId')
                ->setParameter('userId', $this->security->getUser());
        }

        /* Recherche si non inscrit */
        if ($filtre->isSortieNonInscrit()) {
            $query->andWhere(':user NOT IN s.participants')
                ->setParameter('user', $this->security->getUser());
        }

        /* Recherche si sortie passée */
        if ($filtre->isSortiePassee()) {
            $query->andWhere('s.dateLimiteInscription < :dateDuJour')
                ->setParameter('dateDuJour', $dateDuJour);
        }

        return $query->getQuery()->getResult();

    }
}
