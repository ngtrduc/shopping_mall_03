<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
use Application\Entity\ProductMaster;
use Application\Entity\ProductColorImage;
use Application\Entity\category;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Admin\Helper\TrunCate;
/**
 * 
 */
class CategoryController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
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


    private $sessionContainer;

    private $categoryManager;
    /**
     * Constructor.
     */
    public function __construct($entityManager, $sessionContainer, $categoryManager)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
        $this->categoryManager = $categoryManager;
    }

    /**
     * The "view" action displays a page .
     */
    public function viewAction()
    {   

        $categoryId = $this->params()->fromRoute('id', -1);
        $colorName = $this->params()->fromRoute('color', -1);
        $sort = $this->params()->fromQuery('sort', -1);
        
        $arr = [];
        $arr = $this->categoryManager->arrCate($arr,$categoryId);
        $option =$_GET;
        $page = $this->params()->fromQuery('page', 1);

        $category = $this->entityManager->getRepository(category::class)->find($categoryId);

        if($this->getRequest()->isPost()){
            $data = $_POST;
            $products = $this->entityManager->getRepository(ProductColorImage::class)
                ->findProductsByCategory($arr,$data);

        } elseif ($colorName != -1) {
            $color = array_flip($this->color);
            $color_id = $color[$colorName];
            $products = $this->entityManager->getRepository(ProductColorImage::class)
                ->findProductsByColor($arr,$color_id);
                
        } else if ($option != null){

            $products = $this->entityManager->getRepository(ProductColorImage::class)
                ->findProductsBySort($arr, $option);
                
        } else {
            $products = $this->entityManager->getRepository(ProductColorImage::class)
                ->findProductsByCategory($arr);
        }
           
        $adapter = new DoctrineAdapter(new ORMPaginator($products, false));
        
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(9);                
        $paginator->setCurrentPageNumber($page);

        $mainCategories = $this->categoryManager->mainCategories();
        $arrCateTree = $this->categoryManager->arrCateTree();
        
        
        //var_dump($paginator);die();
        $view = new ViewModel([
            'products' => $paginator,
            'category' => $category,
            'mainCategories' => $mainCategories,
            'arrCateTree' => $arrCateTree,
            'sort' => $sort,
            
        ]);
        $this->layout('application/layout');
        return $view;
    }
}
