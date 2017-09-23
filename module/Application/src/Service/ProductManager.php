<?php

namespace Application\Service;

use Application\Entity\Product;
use Application\Entity\ProductMaster;
use Application\Entity\ProductColorImage;
use Application\Entity\Image;
use Application\Entity\User;
use Application\Entity\OrderItem;
use Zend\Filter\StaticFilter;
use Application\Entity\Comment;
use Application\Entity\Review;


class ProductManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $color = [
        ProductMaster::WHITE => 'White',
        ProductMaster::BLACK => 'Black',
        ProductMaster::YELLOW => 'Yellow',
        ProductMaster::RED => 'Red',
        ProductMaster::GREEN => 'Green',
        ProductMaster::PURPLE => 'Purple',
        ProductMaster::ORANGE => 'Orange',
        ProductMaster::BLUE => 'Blue',
        ProductMaster::GREY => 'Grey',
    ];
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCommentCountStr($product)
    {
        $commentCount = count($product->getComments());
        if ($commentCount == 0)
            return 'No comments';
        else if ($commentCount == 1)
            return '1 comment';
        else
            return $commentCount . ' comments';
    }

    // This method adds a new comment to .
    public function addComment($product, $data)
    {
        // Create new Comment entity.
        $user = $this->entityManager->
            getRepository('Application\Entity\User')->find($data['user_id']);
        $comment = new Comment();
        $comment->setProduct($product);
        $comment->setUser($user);
        $comment->setContent($data['content']);
        if ($data['comment_id'] != null) {
            $parent = $this->entityManager->
            getRepository(Comment::class)->find($data['comment_id']);
            $comment->setParent($parent);
        }
        $currentDate = date('Y-m-d H:i:s');
        $comment->setDateCreated($currentDate);

        // Add the entity to entity manager.
        $this->entityManager->persist($comment);

        // Apply changes.
        $this->entityManager->flush();

        return $comment;
    }

    public function addReview($product, $data)
    {   
        
        $user = $this->entityManager
            ->getRepository('Application\Entity\User')->find($data['user_id']);
        $review = new Review();
        
        $review->setUser($user);
        $review->setContent($data['content']);
        $review->setRate($data['rate']);
        $currentDate = date('Y-m-d H:i:s');
        $review->setDateCreated($currentDate);
        $review->setProduct($product);
        // Add the entity to entity manager.
        $this->entityManager->persist($review);

        // Apply changes.
        $this->entityManager->flush();

        return $review;
    }

    public function deleteComment($data)
    {
        $comment = $this->entityManager->
        getRepository(Comment::class)->find($data['comment_id']);

        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }

    public function getCountSellsInMonth($productId)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        $count = 0;

        foreach ($product->getProductMasters() as $productMaster) {
            $count = $count + count($productMaster->findOrderByMonth());
        }

        return $count;
    }

    public function getBestSellsInCurrentMonth($countOfBest)
    {
        $arr = [];
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        foreach ($products as $product) {
            if (count($arr) < $countOfBest) {
                $arr[] = $product->getId();

            } else {
                if ($this->getCountSellsInMonth($product->getId()) > min($arr)) {
                    sort($arr);
                    $arr[0] = $product->getId();
                }
            }
        }

        return $arr;
    }

    public function getBestSaleProduct($countOfBest)
    {
        $arr = [];
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        foreach ($products as $product) {

            if (count($arr) < $countOfBest) {
                $arr[$product->getId()] = $product->getCurrentSale();

            } else {
                asort($arr);
                if ($product->getCurrentSale() > current($arr)) {

                    unset($arr[key($arr)]);

                    $arr[$product->getId()] = $product->getCurrentSale();
                }
            }
        }

        return $arr;
    }


    
}
