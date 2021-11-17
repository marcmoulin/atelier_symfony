<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function remplacerContenu(string $contenuARemplacer, string $contenuRemplacant){
        $qb = $this->createQueryBuilder("m");
        $qb ->update()
            ->set("m.contenu", ":REMPLACANT")
            ->where("m.contenu LIKE :A_REMPLACER")
            ->setParameter("A_REMPLACER", "%".$contenuARemplacer."%")
            ->setParameter("REMPLACANT", $contenuRemplacant);
        $query = $qb->getQuery();

        $query->execute();
        // sauvegarde en base
        $this->getEntityManager()->flush();
    }

    public function findChoixMultiples(int $idUtilisateur=null, string $contenu=null){
        $qb = $this->createQueryBuilder("m");
        if($idUtilisateur != null) {
            $qb->join("m.auteurMessage","a");
            $qb->where("a.id = :IDUTILISATEUR");
            $qb->setParameter("IDUTILISATEUR", $idUtilisateur);
        }
        if($contenu != null){
            $qb->andWhere("m.contenu LIKE :CONTENU");
            $qb->setParameter("CONTENU","%".$contenu."%");
        }
        $query = $qb->getQuery();

        return $query->getResult();
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
