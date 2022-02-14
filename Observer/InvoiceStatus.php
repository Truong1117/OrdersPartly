<?php

namespace Commercers\OrdersPartly\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Model\AbstractModel;
use Commercers\OrdersPartly\Model\InvoiceStatusFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Commercers\OrdersPartly\Helper\Config;

class InvoiceStatus implements ObserverInterface
{
    protected $_invoiceStatusFactory;

    protected $_orderFactory;

    protected $_orderRepository;

    protected $_helpConfigData;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        InvoiceStatusFactory  $invoiceStatusFactory,
        OrderRepositoryInterface $orderRepository,
        Config $helpConfigData
    ) {
        $this->_orderFactory = $orderFactory;
        $this->_invoiceStatusFactory = $invoiceStatusFactory;
        $this->_orderRepository = $orderRepository;
        $this->_helpConfigData = $helpConfigData;
    }

    public function execute(Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $orderId = $invoice->getOrderId();

        $orderData = $this->_orderRepository->get($orderId);
        $invoiceStatusFactory = $this->_invoiceStatusFactory->create();
        $orderItems = $orderData->getAllItems();

        $sumQtyOrders = 0;
        $sumQtyInvoice = 0;

        foreach ($orderItems as $_item) {
            $qtyOrdered = (int)$_item->getQtyOrdered();
            $sumQtyOrders += $qtyOrdered;
            if($_item->getQtyInvoiced()){
                $qtyInvoice = (int)$_item->getQtyInvoiced();
            }
            $sumQtyInvoice += $qtyInvoice;
        }

        $sumItemsQtyInvoice = 0;
        foreach ($invoice->getAllItems() as $item) {
            $sumItemsQtyInvoice += (int)$item->getQty();
            $sumItemsQtyInvoice += $sumQtyInvoice;
        }

        $filterStatuses = explode(',',$this->_helpConfigData->getOrderStatus());
        $orderFactory = $this->_orderFactory->create()->load($orderId);

        if(in_array($orderFactory->getStatus(),$filterStatuses)){
            if($sumQtyOrders > $sumItemsQtyInvoice){
                $invoiceStatusFactory->setInvoiceStatus('partly');
            }
            if($sumQtyOrders == $sumItemsQtyInvoice){
                $invoiceStatusFactory->setInvoiceStatus('fully');
            }
        }

        $invoiceStatusFactory->setOrderId($orderId);
        $invoiceStatusFactory->save();

        return $this;

    }

}
