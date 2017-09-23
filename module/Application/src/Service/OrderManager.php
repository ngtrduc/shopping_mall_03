<?php
namespace Application\Service;

use Application\Entity\Order;
use Application\Entity\ProductMaster;
use Application\Entity\Product;
use Application\Entity\OrderItem;
use Application\Entity\User;
use Application\Entity\Address;
use Zend\Filter\StaticFilter;


class OrderManager 
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $list_color = [
        ProductMaster::WHITE => 'white',
        ProductMaster::BLACK => 'black',
        ProductMaster::YELLOW => 'yellow',
        ProductMaster::RED => 'red',
        ProductMaster::GREEN => 'green',
        ProductMaster::PURPLE => 'purple',
        ProductMaster::ORANGE => 'orange',
        ProductMaster::BLUE => 'blue',
        ProductMaster::GREY => 'grey',
        ];

    private $list_size = [
        ProductMaster::S => 'S',
        ProductMaster::M => 'M',
        ProductMaster::L => 'L',
        ProductMaster::XL => 'XL',
        ProductMaster::XXL => 'XXL',
    ];

    private $entityManager;
    
    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addNewOrder($data, $cart)
    {
        $arrItems = $cart->items;
        $date_created = date('Y-m-d H:i:s');

        //create address
        // $district = $this->entityManager->getRepository(District::class)
        //     ->find($data['district']);

        // $address = new Address();
        // $address->setDistrict($district);
        // $address->setAddress($data['address']);
        // $address->setDateCreated($date_created);
        $address = $this->entityManager->getRepository(Address::class)
                    ->find(1);
        
        $order = new Order();
        
        $order->setName($data['full_name']);
        $order->setPhone($data['phone_number']);

        $order->setAddress($address);

        $order->setNumberOfItems($cart->totalItem);
        $order->setCost($data['total_price']);
        $order->setStatus(Order::STATUS_PENDING);
        $order->setDateCreated($date_created);
        if ($data['user_id'] != null) {
            $user = $this->entityManager
                ->getRepository(User::class)->find($data['user_id']);
            $order->setUser($user);
        }    
        
        $this->entityManager->persist($order);

        foreach ($arrItems as $item) {
            $arr = explode("_", $item->id);
            $color_id = array_search($arr[1], $this->list_color);
            $size_id = array_search($arr[2], $this->list_size);

            $orderItem = new OrderItem();
            
            $product = $this->entityManager->
                getRepository(Product::class)->find((int)$arr[0]);
            $productMasters = $product->getProductMasters();
            
            foreach ($productMasters as $pM) {
                if ($pM->getColorId() == $color_id && $pM->getSizeId() == $size_id) {
                    $orderItem->setProductMaster($pM);
                    break;
                }
            }

            $orderItem->setOrder($order);
            $orderItem->setQuantity($item->quantity);
            $orderItem->setCost($item->price);
            //$orderItem->setStatus($data['status']);
            $orderItem->setDateCreated($date_created);

            $this->entityManager->persist($orderItem);
        }

        $this->entityManager->flush();
        return $order;
    }

    public function getOrder($order_id, $phone) 
    {

        $order = $this->entityManager->getRepository(Order::class)->find($order_id);
        if ($phone == $order->getPhone()) return null;
        $orderItems = $order->getOrderItems();
        $items = [];
        foreach ($orderItems as $orderItem) {
            $items[] = $orderItem->getInfo();
        }
        $address = $order->getAddress()->getInfo();
        $order = [
                'id' => $order_id,
                'created_at' => $order->getDateCreated(),
                'items' => $items,
                'customer_detail' => [
                    'full_name' => $order->getName(),
                    'phone_number' => $order->getPhone(),
                    'address' => $address['address'] . ' - ' . $address['district'] . ' - ' . $address['province'],
                ],
                'total_price' => $order->getCost(),
                'status' => $order->getStatus(),
            ];

        return $order;
    }
}
