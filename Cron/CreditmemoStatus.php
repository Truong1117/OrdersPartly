<?php

namespace Commercers\OrdersPartly\Cron;

use Commercers\OrdersPartly\Model\CreditmemoStatusFactory;
use Commercers\OrdersPartly\Helper\Config;
use Magento\Sales\Model\Order;

class CreditmemoStatus
{
    protected $_creditStatusFactory;

    protected $_orderFactory;

    protected $_helpConfigData;

    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        CreditmemoStatusFactory  $creditStatusFactory,
        Config $helpConfigData
    )
    {
        $this->_orderFactory = $orderFactory;
        $this->_creditStatusFactory = $creditStatusFactory;
        $this->_helpConfigData = $helpConfigData;
    }

    public function execute()
    {
        if(!$this->_helpConfigData->isEnabled()){
            return;
        }

        $creditStatusFactory = $this->_creditStatusFactory->create();
        $collection = $creditStatusFactory->getCollection();

        $creditStatusPartly = $this->_helpConfigData->getCreditStatusPartly();
        $daysBack = $this->_helpConfigData->getDayBack();
        
        if($daysBack <= 1){
            $daysBackTime = " ".$daysBack." day";
        } else {
            $daysBackTime = " ".$daysBack." days";
        }

        $date = date('Y-m-d H:i:s');
        $checkDate = date('Y-m-d H:i:s', strtotime($date. " - ".$daysBackTime));
        $collection->addFieldToFilter('created_at', ['from'=>$checkDate, 'to'=>$date]);
        $collection->addFieldToFilter('created_at', ['from'=>$checkDate, 'to'=>$date]);

        if($daysBack <= 1){
            $daysBackTime = " ".$daysBack." day";
        } else {
            $daysBackTime = " ".$daysBack." days";
        }

        $date = date('Y-m-d H:i:s');
        $checkDate = date('Y-m-d H:i:s', strtotime($date. " - ".$daysBackTime));
        $collection->addFieldToFilter('created_at', ['from'=>$checkDate, 'to'=>$date]);

        foreach($collection as $item){
            if($item->getCreditmemoStatus() == 'partly'){
                $order = $this->_orderFactory->create()->load($item->getOrderId());
                if($order->getState() == "complete"){
                    $order->setStatus($creditStatusPartly);
                    $order->save();
                } elseif($order->getState() == "processing"){
                    $order->setStatus($creditStatusPartly);
                    $order->save();
                }

            }
            if($item->getInvoiceStatus() == 'fully'){
                $order = $this->_orderFactory->create()->load($item->getOrderId());
                $order->setData('state', 'closed');
                $order->setStatus('closed');

                $order->save();
            }
        }

        return $this;
    }

}
