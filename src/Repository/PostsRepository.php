<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posts>
 *
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

    public function save(Posts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Posts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function savePosts($allPosts,$users)
    {
        // print_r($allPosts);die;
        $em=$this->getEntityManager();
        try{
            foreach ($allPosts as $key => $value) {
                
                $post=new Posts;
                $post->setTitle($value->title);
                $post->setBody($value->body);
                $post->setBody($value->body);
                $userId=(int)$value->userId;
                $post->setUserId($userId);
                $name='';
                if(isset($users[$userId]['name']))
                    $name=$users[$userId]['name']; 
                $post->setUserName($name);
                $surname='';
                if(isset($users[$userId]['surname']))
                    $surname=$users[$userId]['surname'];
                $post->setUserSurname($surname);
                
                $em->persist($post);
            
            }
            
            $em->flush();
            $em->clear();
            return 'success';
        }catch(\Exception $e){
            return 'error';
        }

        
    }
 
    

//    /**
//     * @return Posts[] Returns an array of Posts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Posts
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
