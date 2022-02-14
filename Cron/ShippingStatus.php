<?php

namespace Commercers\OrdersPartly\Cron;

use Commercers\OrdersPartly\Model\ShippingStatusFactory;
use Magento\Sales\Model\Order;
use Commercers\OrdersPartly\Helper\Config;

class ShippingStatus
{
    protected $_orderFactory;

    protected $_shippingStatusFactory;

    protected $_helpConfigData;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        ShippingStatusFactory  $shippingStatusFactory,
        Config $helpConfigData
    )
    {
        $this->_orderFactory = $orderFactory;
        $this->_shippingStatusFactory = $shippingStatusFactory;
        $this->_helpConfigData = $helpConfigData;
    }
    public function execute()
    {
        if(!$this->_helpConfigData->isEnabled()){
            return;
        }

        $shippingStatusFactory = $this->_shippingStatusFactory->create();
        $collection = $shippingStatusFactory->getCollection();
        $shippingStatusPartly = $this->_helpConfigData->getShippingStatusPartly();
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
            if($item->getShippingStatus() == 'partly'){
                $order = $this->_orderFactory->create()->load($item->getOrderId());
                if($order->getState() == "complete"){
                    $order->setStatus($shippingStatusPartly);
                } elseif($order->getState() == "processing"){
                    $order->setStatus($shippingStatusPartly);
                }
                $order->save();

            } elseif($item->getShippingStatus() == 'fully'){
                $order = $this->_orderFactory->create()->load($item->getOrderId());
                if($order->getState() == "complete"){
                    $order->setStatus('complete');
                } elseif($order->getState() == "processing"){
                    $order->setStatus('processing');
                }
                $order->save();
            }
        }

        return $this;
    }

}
