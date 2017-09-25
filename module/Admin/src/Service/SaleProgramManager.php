<?php
namespace Admin\Service;

use Application\Entity\SaleProgram;
use Application\Entity\Sale;
use Application\Entity\Product;
use Zend\Filter\StaticFilter;

class SaleProgramManager 
{
	/**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addNewSaleProgram($data) 
    {
        $saleProgram = new SaleProgram();
        $saleProgram->setName($data['name']);
        $saleProgram->setDateStart($data['date_start']);
        $saleProgram->setDateEnd($data['date_end']);
        $saleProgram->setStatus($this->get_status_depend_on_time(
            $data['date_start'], $data['date_end']));
        $currentDate = date('Y-m-d H:i:s');
        $saleProgram->setDateCreated($currentDate);       
            
        // Add the entity to entity manager.
        $this->entityManager->persist($saleProgram);
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        return 1;
    }

    public function updateSaleProgram($saleProgram, $data)
    {
        $saleProgram->setName($data['name']);
        $saleProgram->setDateStart($data['date_start']);
        $saleProgram->setDateEnd($data['date_end']);
        $saleProgram->setStatus($this->get_status_depend_on_time(
            $data['date_start'], $data['date_end']));

        $this->entityManager->flush();

        return 1;
    }

    public function cancelSaleProgram($saleProgram)
    {
        $saleProgram->setStatus(SaleProgram::CANCEL);
        $products = $saleProgram->getProducts();
        for($i = 0; $i < count($products); $i++) {
            $current_price = (int)($products[$i]->getPrice()*(100 - $products[$i]->getCurrentSale())/100);
            $products[$i]->setCurrentPrice($current_price); 
        }
        $this->entityManager->flush();
    }

    public function setStatusDependOnTime($saleProgram)
    {
        $saleProgram->setStatus($this->get_status_depend_on_time(
            $saleProgram->getDateStart(), $saleProgram->getDateEnd()));

        $this->entityManager->flush();
    }

    public function addProductToSaleProgram($saleProgram, $data)
    {
        for ($i = 0; $i < count($data['products']['id']); $i++){ 
            $sale = new Sale();
            $product = $this->entityManager->getRepository(Product::class)
                ->findOneById($data['products']['id'][$i]);

            $sale->setSaleProgram($saleProgram);
            
            $sale->setSale($data['products']['sale'][$i]);

            $currentDate = date('Y-m-d H:i:s');
            $sale->setDateCreated($currentDate);
            $sale->setProduct($product);
            
            $this->entityManager->persist($sale);
        }            

        $this->entityManager->flush();
    }

    public function removeProductOutOfSaleProgram($data)
    {
        $saleProgram = $this->entityManager->getRepository(SaleProgram::class)
            ->findOneById($data['sale_program_id']);
        $product = $this->entityManager->getRepository(Product::class)
            ->findOneById($data['product_id']);

        $saleProgram->removeProductAssociation($product);
        
        $this->entityManager->flush();
    }

    public function getSaleArray($saleProgram)
    {   
        $sales = $saleProgram->getSales();
        foreach ($sales as $s) {
            $sale_array[$s->getProduct()->getId()] = $s->getSale();
        }
        return $sale_array;
    }

    public function getSaleProgramNeedActive()
    {
        $currentDate = date('d-m-Y');
        $salePrograms = $this->entityManager->getRepository(SaleProgram::class)
            ->findBy(['status' => SaleProgram::PENDING]);
        $list = [];
        foreach ($salePrograms as $sP) {
            $date_start = $sP->getDateStart();
            $date_start = date('d-m-Y', strtotime($date_start));
            $date_end = $sP->getDateEnd();
            $date_end = date('d-m-Y', strtotime($date_end));
            if ($date_start <= $currentDate && $date_end >= $currentDate) {
                array_push($list, $sP);
            }
        }

        return $list;
    }

    public function getSaleProgramNeedDone()
    {
        $currentDate = date('d-m-Y');
        $salePrograms = $this->entityManager->getRepository(SaleProgram::class)
            ->findBy(['status' => SaleProgram::ACTIVE]);
        $list = [];
        foreach ($salePrograms as $sP) {
            $date_end = $sP->getDateEnd();
            $date_end = date('d-m-Y', strtotime($date_end));
            if ($date_end < $currentDate) {
                array_push($list, $sP);
            }
        }

        return $list;
    }

    private function get_status_depend_on_time($date_start, $date_end)
    {
        $currentDate = date('d-m-Y');
        $date_start = str_replace('/', '-', $date_start);
        $date_start = date('d-m-Y', strtotime($date_start));
        $date_end = str_replace('/', '-', $date_end);
        $date_end = date('d-m-Y', strtotime($date_end));
        if ($currentDate < $date_start)
            return SaleProgram::PENDING;
        elseif ($currentDate <= $date_end)
            return SaleProgram::ACTIVE;
        if ($currentDate > $date_end)
            return SaleProgram::DONE;
    }
}
