<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\ProductColorImage;


/**
 * This is the custom repository class for Post entity.
 */
class ProductColorImageRepository extends EntityRepository
{
	public function findProductsByColor($arr, $color_id) {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('pm')
            ->from(ProductColorImage::class, 'pm')
            ->join('pm.product', 'p')
            ->join('p.category', 'c')
            ->where('c.id IN(:arr)')
            ->andWhere('pm.color_id = :color_id')
            ->setParameters(array('arr' => $arr,'color_id' => $color_id))
            ->distinct(true);

        return $queryBuilder->getQuery();
    }

    public function findProductsByCategory($arr, $data = null)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('pm')
            ->from(ProductColorImage::class, 'pm')
            ->join('pm.product', 'p')
            ->join('p.category', 'c')
            ->where('c.id IN(:arr)');
        if ($data == null) 
            $queryBuilder->setParameter('arr', $arr);
        else {
            $queryBuilder->andWhere($queryBuilder->expr()->between('p.price', '?1', '?2'))
            ->setParameters(['arr' => $arr, 1 => $data['price1'], 2 => $data['price2']]);
        }
        return $queryBuilder->getQuery();
    }

    public function findProductsBySort($arr ,$option = null)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('pm')
            ->from(ProductColorImage::class, 'pm')
            ->join('pm.product', 'p')
            ->join('p.category', 'c')
            ->where('c.id IN(:arr)')
            ->setParameter('arr', $arr); 
        switch ($option['sort']) {
            case 'htl':
                $queryBuilder->orderBy('p.price', 'DESC');  
                break;
            case 'lth':
                $queryBuilder->orderBy('p.price', 'ASC');  
                break; 
            case 'new':
                $queryBuilder->orderBy('pm.date_created', 'DESC');  
                break;
            
            default:
                
                break;
        };
        return $queryBuilder->getQuery();
    }


}