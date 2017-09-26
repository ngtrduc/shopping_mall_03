<?php
namespace Admin\Service;

use Application\Entity\Order;
use Application\Entity\Review;
use Zend\Filter\StaticFilter;

class StatisticManager
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

    public function getOrderStatistic($type)
    {
        if ($type == 1)
            return $this->makeOrderDataChartByMonth();
        else return $this->makeOrderDataChartByYear();
    }

    private function makeOrderDataChartByMonth()
    {
        $data_week[0] = $this->getOrderQuantityByWeek(1)[0][1];
        $data_week[1] = $this->getOrderQuantityByWeek(8)[0][1];
        $data_week[2] = $this->getOrderQuantityByWeek(15)[0][1];
        $data_week[3] = $this->getOrderQuantityByWeek(22)[0][1];

        $data = $this->getPreConfigChart('orders');
        $data['subtitle']['text'] = 'Order By Month';
        $data['xAxis']['categories'] = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $data['series'][0]['data'] = $data_week;

        return $data;
    }

    private function makeOrderDataChartByYear()
    {
        for ($i=0; $i < 12 ; $i++) { 
            $data_month[$i] = $this->getOrderQuantityByMonth($i + 1)[0][1];
        }

        $data = $this->getPreConfigChart('orders');
        $data['subtitle']['text'] = 'Order By Year';
        $data['xAxis']['categories'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data['series'][0]['data'] = $data_month;

        return $data;
    }

    private function getOrderQuantityByMonth($month)
    {
        $currentYear = date("Y");
        $currentMonth = $currentYear."-".$month."-01";
        $nextMonth = date("Y-m-d", strtotime("+1 month", strtotime($currentMonth)));
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('count(o.date_created)')
            ->from(Order::class, 'o')
            ->where('o.date_created >= :month')
            ->andWhere('o.date_created < :nextmonth')
            ->setParameter('month', $currentMonth)
            ->setParameter('nextmonth', $nextMonth);

        return $queryBuilder->getQuery()->getResult();
    }

    private function getOrderQuantityByWeek($day)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentMonth = date("Y-m");
        $currentWeekDay = $currentMonth."-".$day;
        if ($day == 22){
            $currentMonth = date("Y-m")."-01";
            $currentMonth = strtotime($currentMonth);
            $nextWeekDay = date("Y-m-d", strtotime("+1 month", $currentMonth));
        }
        else $nextWeekDay = $currentMonth."-".($day+7);

        $queryBuilder->select('count(o.date_created)')
            ->from(Order::class, 'o')
            ->where('o.date_created >= :weekday')
            ->andWhere('o.date_created < :nextweek')
            ->setParameter('weekday', $currentWeekDay)
            ->setParameter('nextweek', $nextWeekDay);

        return $queryBuilder->getQuery()->getResult();
    }

    // review Chart 
    public function getReviewStatistic($type)
    {
        if ($type == 1)
            return $this->makeReviewDataChartByMonth();
        else return $this->makeReviewDataChartByYear();
    }

    private function makeReviewDataChartByMonth()
    {
        $data_week[0] = $this->getReviewQuantityByWeek(1)[0][1];
        $data_week[1] = $this->getReviewQuantityByWeek(8)[0][1];
        $data_week[2] = $this->getReviewQuantityByWeek(15)[0][1];
        $data_week[3] = $this->getReviewQuantityByWeek(22)[0][1];

        $data = $this->getPreConfigChart('reviews');
        $data['subtitle']['text'] = 'Review By Month';
        $data['xAxis']['categories'] = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        $data['series'][0]['data'] = $data_week;

        return $data;
    }

    private function makeReviewDataChartByYear()
    {
        for ($i=0; $i < 12 ; $i++) { 
            $data_month[$i] = $this->getReviewQuantityByMonth($i + 1)[0][1];
        }

        $data = $this->getPreConfigChart('reviews');
        $data['subtitle']['text'] = 'Review By Year';
        $data['xAxis']['categories'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data['series'][0]['data'] = $data_month;

        return $data;
    }

    private function getReviewQuantityByMonth($month)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentYear = date("Y");
        $currentMonth = $currentYear."-".$month."-01";
        $nextMonth = date("Y-m-d", strtotime("+1 month", strtotime($currentMonth)));

        $queryBuilder->select('count(o.date_created)')
            ->from(Review::class, 'o')
            ->where('o.date_created >= :month')
            ->andWhere('o.date_created < :nextmonth')
            ->setParameter('month', $currentMonth)
            ->setParameter('nextmonth', $nextMonth);

        return $queryBuilder->getQuery()->getResult();
    }

    private function getReviewQuantityByWeek($day)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        
        $currentMonth = date("Y-m");
        $currentWeekDay = $currentMonth."-".$day;
        if ($day == 22){
            $currentMonth = date("Y-m")."-01";
            $currentMonth = strtotime($currentMonth);
            $nextWeekDay = date("Y-m-d", strtotime("+1 month", $currentMonth));
        }
        else $nextWeekDay = $currentMonth."-".($day+7);

        $queryBuilder->select('count(o.date_created)')
            ->from(Review::class, 'o')
            ->where('o.date_created >= :weekday')
            ->andWhere('o.date_created < :nextweek')
            ->setParameter('weekday', $currentWeekDay)
            ->setParameter('nextweek', $nextWeekDay);

        return $queryBuilder->getQuery()->getResult();
    }

    private function getPreConfigChart($name)
    {
        $data['title']['text'] = $name;
        $data['yAxis']['title']['text'] = 'Number of '.$name;
        $data['legend']['layout'] = 'vertical';
        $data['legend']['align'] = 'right';
        $data['legend']['verticalAlign'] = 'middle';
        $data['plotOptions']['line']['dataLabels']['enabled'] = true;
        $data['series'][0]['name'] = $name.' Quantity';

        return $data;
    }
}
    