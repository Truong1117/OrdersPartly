<?php

namespace Commercers\OrdersPartly\Observer;

use Commercers\OrdersPartly\Helper\Config;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Model\AbstractModel;
use Commercers\OrdersPartly\Model\CreditmemoStatusFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class CreditmemoStatus implements ObserverInterface
{
    protected $_creditStatusFactory;

    protected $_orderRepository;

    protected $_helpConfigData;

    protected $_orderFactory;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        CreditmemoStatusFactory  $creditStatusFactory,
        OrderRepositoryInterface $orderRepository,
        Config $helpConfigData
    ) {
        $this->_creditStatusFactory = $creditStatusFactory;
        $this->_orderRepository = $orderRepository;
        $this->_helpConfigData = $helpConfigData;
        $this->_orderFactory = $orderFactory;
    }

    public function execute(Observer $observer)
    {
        $credit = $observer->getEvent()->getCreditmemo();
        $orderId = $credit->getOrderId();
        $orderData = $this->_orderRepository->get($orderId);

        $creditStatusFactory = $this->_creditStatusFactory->create();
        $orderItems = $orderData->getAllItems();

        $sumQtyOrders = 0;
        $sumQtyRefund = 0;

        foreach ($orderItems as $_item) {
            $qtyOrdered = (int)$_item->getQtyOrdered();
            $sumQtyOrders += $qtyOrdered;
            if($_item->getQtyRefunded()){
                $qtyRefund = (int)$_item->getQtyRefunded();
            }
            $sumQtyRefund += $qtyRefund;
        }

        $sumItemsQtyCredit = 0;
        foreach ($credit->getAllItems() as $item) {
            $sumItemsQtyCredit += $item->getQty();
            $sumItemsQtyCredit += $sumQtyRefund;
        }

        $filterStatuses = explode(',',$this->_helpConfigData->getOrderStatus());
        $orderFactory = $this->_orderFactory->create()->load($orderId);

        if(in_array($orderFactory->getStatus(),$filterStatuses)){
            if($sumQtyOrders > $sumItemsQtyCredit){
                $creditStatusFactory->setCreditmemoStatus('partly');
            }
            if($sumQtyOrders == $sumItemsQtyCredit){
                $creditStatusFactory->setCreditmemoStatus('fully');
            }
        }

        $creditStatusFactory->setOrderId($orderId);
        $creditStatusFactory->save();

        return $this;

    }

}

