<?php

namespace Commercers\OrdersPartly\Observer;

use Commercers\OrdersPartly\Helper\Config;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Model\AbstractModel;
use Commercers\OrdersPartly\Model\ShippingStatusFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class ShippingStatus implements ObserverInterface
{
    protected $_shippingStatusFactory;

    protected $_orderRepository;

    protected $_helpConfigData;

    protected $_orderFactory;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        ShippingStatusFactory $shippingStatusFactory,
        OrderRepositoryInterface $orderRepository,
        Config $helpConfigData
    )
    {
        $this->_shippingStatusFactory = $shippingStatusFactory;
        $this->_orderRepository = $orderRepository;
        $this->_helpConfigData = $helpConfigData;
        $this->_orderFactory = $orderFactory;
    }

    public function execute(Observer $observer)
    {
        $shipment = $observer->getEvent()->getShipment();
        $orderId = $shipment->getOrderId();

        $orderData = $this->_orderRepository->get($orderId);
        $shippingStatusFactory = $this->_shippingStatusFactory->create();
        $orderItems = $orderData->getAllItems();

        $sumQtyOrders = 0;
        $sumItemsQtyShipping = 0;

        foreach ($orderItems as $_item) {
            $qtyOrdered = (int)$_item->getQtyOrdered();
            $sumQtyOrders += $qtyOrdered;
            if($_item->getQtyShipped()){
                $qtyShipped = (int)$_item->getQtyShipped();
            }
            $sumItemsQtyShipping += $qtyShipped;
        }

        $filterStatuses = explode(',',$this->_helpConfigData->getOrderStatus());
        $orderFactory = $this->_orderFactory->create()->load($orderId);

        if(in_array($orderFactory->getStatus(),$filterStatuses)){
            if($sumQtyOrders > $sumItemsQtyShipping){
                $shippingStatusFactory->setShippingStatus('partly');
            }
            if($sumQtyOrders == $sumItemsQtyShipping){
                $shippingStatusFactory->setShippingStatus('fully');
            }
        }

        $shippingStatusFactory->setOrderId($orderId);
        $shippingStatusFactory->save();

        return $this;
    }

}

