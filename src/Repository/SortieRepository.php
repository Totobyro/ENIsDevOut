<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Form;
use App\Model\Filtre;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findByFilters(Form $form, UserInterface $user)
    {

        $filtre = new Filtre();
        $filtre = $form->getData();
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');

        if ($form->isSubmitted()) {
            
            if ($filtre->getRecherche()!=null && $filtre->getRecherche()!="") {
                $qb->andWhere('s.nom LIKE :recherche');
                $qb->setParameter('recherche', '%' . $filtre->getRecherche() . '%');
            }
            
            if ($filtre->getCampus()!=null) {
                $qb->andWhere('s.campus = :idcampus');
                $qb->setParameter('idcampus', $filtre->getCampus()->getId());
            }
            
            if ($filtre->getDateDebut()!=null) {
                $qb->andWhere('s.dateHeureDebut > :dateDebut');
                $qb->setParameter('dateDebut', $filtre->getDateDebut());

            }

            if ($filtre->getDateFin()!=null) {
                $qb->andWhere('s.dateHeureDebut < :dateFin');
                $qb->setParameter('dateFin', $filtre->getDateFin());
            }

            if ($filtre->getSortieOrganisateur()) {
                $qb->andWhere('s.organisateur = :idorganisateur');
                $qb->setParameter('idorganisateur', $user->getId());
            }

            if ($filtre->getSortieInscrit()) {
                $qb->andWhere(':idparticipant MEMBER OF s.participants');
                $qb->setParameter('idparticipant', $user->getId());
            }

            if ($filtre->getSortieNonInscrit()) {
                $qb->andWhere(':idparticipant NOT MEMBER OF s.participants');
                $qb->setParameter('idparticipant', $user->getId());
            }

            if ($filtre->getSortiePasse()) {
                $qb->join('s.etat', 'e');
                $qb->addSelect('e');
                $qb->andWhere('e.libelle LIKE :passe');
                $qb->setParameter('passe', '%Passée%');
            }
        }

        return $qb->getQuery()->getResult();
    }


    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
