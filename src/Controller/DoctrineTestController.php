<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use App\Repository\ProduitRepository;
use App\Repository\SujetRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctrineTestController extends AbstractController
{
    /**
     * @param MessageRepository $messageRepository
     * @Route("/rep4")
     */
    public function rep4(MessageRepository $messageRepository){
        dump($messageRepository->remplacerContenu("vert","Nouveau commentaire"));
    }

        /**
     * @param MessageRepository $messageRepository
     * @Route("/rep3")
     */
    public function rep3(MessageRepository $messageRepository){
        dump($messageRepository->findChoixMultiples());
        dump($messageRepository->findChoixMultiples(1));
        dump($messageRepository->findChoixMultiples(null,"vert"));
        dump($messageRepository->findChoixMultiples(1,"vert"));
    }


    /**
     * Liste les messages des sujets du forum 'De Cadix'
     * @param EntityManagerInterface $entityManager
     * @Route("/qb2")
     */
    public function qb2(EntityManagerInterface $entityManager){
        $qb = $entityManager->createQueryBuilder();
        $qb->select("m");
        $qb->from("App:Message","m");
        $qb->join("m.sujet","s");
        $qb->join("s.forum","f");
        $qb->where("f.label=:LABEL");
        $query = $qb->getQuery();
        $query->setParameter("LABEL","De Cadix");
        dump( $query->getResult() );
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @Route("/qb1")
     */
    public function qb1(EntityManagerInterface $entityManager){
        $qb = $entityManager->createQueryBuilder();
        $qb->select("f");
        $qb->from("App:Forum","f");
        $query = $qb->getQuery();
        dump( $query->getResult() );
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @Route("/query8")
     */
    public function query8(EntityManagerInterface $entityManager){
        $dql = "SELECT prod.label nom, 
                    (
                    SELECT COUNT(u)
                    FROM App:Utilisateur u
                         JOIN u.produitsSuivis p2
                    WHERE  prod=p2
                    ) nb_suivi,
                    (
                    SELECT COUNT(f)
                    FROM App:Forum f 
                    WHERE prod.forum = f 
                    ) nb_forum
                FROM App:Produit prod
        ";
        $query = $entityManager->createQuery($dql);
        dump( $query->getResult() );
    }


        /**
     * @param EntityManagerInterface $entityManager
     * @Route("/query7")
     */
    public function query7(EntityManagerInterface $entityManager){
        $dql = "SELECT prod
                FROM App:Produit prod
                WHERE prod IN(
                    SELECT prod2
                    FROM App:Produit prod2
                        JOIN prod2.utilisateursInteresses util
                ) AND
                    prod IN(
                    SELECT prod3
                    FROM App:Produit prod3
                        JOIN prod3.forum forum
                )
        ";
        $query = $entityManager->createQuery($dql);
        dump( $query->getResult() );
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @Route("/query6")
     */
    public function query6(EntityManagerInterface $entityManager){
        $dql = "SELECT u
                FROM App:Utilisateur u
                    JOIN u.sujets sujets
                WHERE u NOT IN(
                    SELECT u2
                    FROM App:Utilisateur u2
                    JOIN u2.messages m
                )
        ";
        $query = $entityManager->createQuery($dql);
        dump( $query->getResult() );
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @Route("/query5")
     */
    public function query5(EntityManagerInterface $entityManager, ProduitRepository $produitRepository){

        $p1 = $produitRepository->find(2);
        $p2 = $produitRepository->find(3);

        $dql = "SELECT p.label lab, COUNT(f) nb_forums
                FROM App:Produit p
                    LEFT JOIN p.forum f
                WHERE p IN (:PRODUITS)
                GROUP BY p
                ORDER BY nb_forums DESC, lab
                ";
        $query = $entityManager->createQuery($dql);
        $query->setParameter("PRODUITS",[$p1,$p2]);
        dump( $query->getResult() );

    }

    /**
     * @param EntityManagerInterface $entityManager
     * @Route("/query4")
     */
    public function query4(EntityManagerInterface $entityManager){
        $dql = "SELECT u.login, COUNT(m) nb_messages
                FROM App:Utilisateur u
                    LEFT JOIN u.messages m
                GROUP BY u
                HAVING nb_messages < 3
                ";
        $query = $entityManager->createQuery($dql);
        dump( $query->getResult() );
    }
    /**
     * @param EntityManagerInterface $entityManager
     * @Route("/query3")
     */
    public function query3(EntityManagerInterface $entityManager){
        $dql = "SELECT p prod, ui.login
                FROM App:Produit p 
                    LEFT JOIN p.utilisateursInteresses ui
                ";
        $query = $entityManager->createQuery($dql);
        dump( $query->getResult() );

    }

    /**
     * @Route("/query1")
     */
    public function query1(EntityManagerInterface $entityManager){

        $dql = "SELECT  m    
                FROM    App:Message m 
                        JOIN m.auteurMessage u
                WHERE   u.login='tartempion'";
        $query = $entityManager->createQuery($dql);
        dump( $query->getResult() );
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @Route("/query2")
     */
    public function query2(EntityManagerInterface $entityManager){
        $dql = "SELECT m 
                FROM App:Message m
                    JOIN m.sujet s
                    JOIN s.auteur a 
                WHERE a.login='tartempion'";
        $query = $entityManager->createQuery($dql);
        dump($query->getResult());

    }


    /**
     * @Route("/rep1")
     */
    public function rep1(SujetRepository $sujetRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $sujet = $sujetRepository->findOneByForum(3);
        $utilisateur = $utilisateurRepository->findOneByLogin("tartempion");
        dump($utilisateur);
    }
}
