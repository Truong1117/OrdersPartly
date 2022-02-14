<?php

namespace Commercers\OrdersPartly\Cron;

use Commercers\OrdersPartly\Model\InvoiceStatusFactory;
use Magento\Sales\Model\Order;
use Commercers\OrdersPartly\Helper\Config;

class InvoiceStatus
{
    protected $_orderFactory;

    protected $_invoiceStatusFactory;

    protected $_helpConfigData;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        InvoiceStatusFactory  $invoiceStatusFactory,
        Config $helpConfigData
    )
    {
        $this->_orderFactory = $orderFactory;
        $this->_invoiceStatusFactory = $invoiceStatusFactory;
        $this->_helpConfigData = $helpConfigData;
    }
    public function execute()
    {
        if(!$this->_helpConfigData->isEnabled()){
            return;
        }

        $invoiceStatusFactory = $this->_invoiceStatusFactory->create();
        $collection = $invoiceStatusFactory->getCollection();
        $invoiceStatusPartly = $this->_helpConfigData->getInvoiceStatusPartly();
        $daysBack = $this->_helpConfigData->getDayBack();
        $filterStatuses = explode(',',$this->_helpConfigData->getOrderStatus());

        if($daysBack <= 1){
            $daysBackTime = " ".$daysBack." day";
        } else {
            $daysBackTime = " ".$daysBack." days";
        }

        $date = date('Y-m-d H:i:s');
        $checkDate = date('Y-m-d H:i:s', strtotime($date. " - ".$daysBackTime));
        $collection->addFieldToFilter('created_at', ['from'=>$checkDate, 'to'=>$date]);

        foreach($collection as $item){
            if($item->getInvoiceStatus() == 'partly'){
                $order = $this->_orderFactory->create()->load($item->getOrderId());
                if($order->getState() == "complete"){
                    $order->setStatus($invoiceStatusPartly);
                    $order->save();
                } elseif($order->getState() == "processing"){
                    $order->setStatus($invoiceStatusPartly);
                    $order->save();
                }
            }
            if($item->getInvoiceStatus() == 'fully'){
                $order = $this->_orderFactory->create()->load($item->getOrderId());
                if($order->getState() == "complete"){
                    $order->setStatus('complete');
                    $order->save();
                } elseif($order->getState() == "processing"){
                    $order->setStatus('processing');
                    $order->save();
                }
            }
        }
        return $this;
    }

}
