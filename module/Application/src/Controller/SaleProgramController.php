<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\SaleProgram;
use Application\Entity\Product;

class SaleProgramController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $sale_programs = $this->entityManager->getRepository(SaleProgram::class)
            ->findBy(['status' => 0], ['date_start' => 'ASC']);

        $sale_data = [];

        foreach ($sale_programs as $sp) {
            $products = $sp->getProducts();

            $sale = [
                'name' => $sp->getName(),
                'id' => $sp->getId(),
                'date_start' => $sp->getDateStart(),
                'date_end' => $sp->getDateEnd(),
                'products' => [],
                'num_products' => sizeof($products),
            ];

            for ($i = 0; $i < sizeof($products) && $i < 2; $i++) {
                $product = $products[$i];
                $product_data = [
                    'name' => $product->getName(),
                    'id' => $product->getId(),
                    'sale' => $product->getCurrentSale(),
                    'current_price' => $product->getCurrentPrice(),
                    'image' => $product->getImage(),
                ];
                 array_push($sale['products'], $product_data);
            }

            array_push($sale_data, $sale);
        }

        $this->getResponse()->setContent(json_encode($sale_data));
        return $this->getResponse();
    }

    public function viewAction()
    {
        $saleProgramId = $this->params()->fromRoute('id', -1);
        $saleProgram = $this->entityManager->getRepository(SaleProgram::class)
            ->findOneById($saleProgramId);
        if ($saleProgram == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $products = $this->entityManager->getRepository(Product::class)
            ->findAll();
        $products_in_sale = $saleProgram->getProducts();

        $sale_array = $this->saleProgramManager->getSaleArray($saleProgram);

        $currentDate = date('d-m-Y');
        if ($saleProgram->getStatus() == 1 && $saleProgram->getCurrentStatus() == 0) {
            $status_of_status = "Need to Active";
        }
        if ($saleProgram->getStatus() == 0 && $saleProgram->getCurrentStatus() == 2) {
            $status_of_status = "Expired";
        }

        return new ViewModel([
            'products_in_sale' => $products_in_sale,
            'saleProgram' => $saleProgram,
            'products' => $products,
            'currentDate' => $currentDate,
            'sale_array' => $sale_array,
            'status_of_status' => $status_of_status
        ]);
    }
}
