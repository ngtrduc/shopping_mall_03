<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OrderController extends AbstractActionController
{
    private $entityManager;
    private $sessionContainer;
    private $orderManager;
    function __construct($entityManager, $sessionContainer, $orderManager)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
        $this->orderManager = $orderManager;
    }

    function trackAction()
    {
        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getContent();
            $data = json_decode($data);

            // find order by $data->email and $data->phone_number

            // $order = [
            //     'id' => 1,
            //     'created_at' => '',
            //     'items' => [
            //         [
            //             'id' => 1,
            //             'name' => 'Cu cai 1',
            //             'quantity' => 1,
            //             'color' => 'Black',
            //             'size' => 'XL',
            //             'price' => 40,
            //             'total' => 40,
            //         ]
            //     ],
            //     'customer_detail' => [
            //         'full_name' => 'Nguyen Phuc Long',
            //         'email' => 'admin@gmail.com',
            //         'phone_number' => '0123456789',
            //         'address' => 'No1 - Dai Co Viet - Hai Ba Trung - Ha Noi',
            //     ],
            //     'total_price' => 120,
            //     'status' => 0,
            // ];
            // return order by $order_id ; $email
            $order = $this->orderManager->getOrder($order_id, $email);

            $this->response->setContent(json_encode($order));
            return $this->response;
        }

        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }

    function viewAction()
    {
        
        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getContent();
            $data = json_decode($data);
        }

        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }
}
