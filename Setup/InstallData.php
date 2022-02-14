<?php

namespace Commercers\OrdersPartly\Setup;

use Exception;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Status;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\Status as StatusResource;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;

class InstallData implements InstallDataInterface
{

    const ORDER_STATUS_PROCESSING_INVOICE_PARTLY_CODE = 'invoice_partly';
    const ORDER_STATUS_PROCESSING_INVOICE_PARTLY_LABEL = 'Invoice Partly';
    const ORDER_STATUS_PROCESSING_INVOICE_FULLY_CODE = 'invoice_fully';
    const ORDER_STATUS_PROCESSING_INVOICE_FULLY_LABEL = 'Invoice Fully';

    const ORDER_STATUS_PROCESSING_CREDITMEMO_PARTLY_CODE = 'credit_partly';
    const ORDER_STATUS_PROCESSING_CREDITMEMO_PARTLY_LABEL = 'Credit Partly';
    const ORDER_STATUS_PROCESSING_CREDITMEMO_FULLY_CODE = 'credit_fully';
    const ORDER_STATUS_PROCESSING_CREDITMEMO_FULLY_LABEL = 'Creditmemo Fully';

    const ORDER_STATUS_PROCESSING_SHIPPING_PARTLY_CODE = 'shipping_partly';
    const ORDER_STATUS_PROCESSING_SHIPPING_PARTLY_LABEL = 'Shipping Partly';
    const ORDER_STATUS_PROCESSING_SHIPPING_FULLY_CODE = 'shipping_fully';
    const ORDER_STATUS_PROCESSING_SHIPPING_FULLY_LABEL = 'Shipping Fully';


    protected $statusFactory;

    protected $statusResourceFactory;

    public function __construct(
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory
    ) {
        $this->statusFactory = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }
    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     *
     * @throws Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->addNewOrderStatusInvoicePartly();
//        $this->addNewOrderStatusInvoiceFully();
        $this->addNewOrderStatusCreditmemoPartly();
//        $this->addNewOrderStatusCreditmemoFully();
        $this->addNewOrderStatusShippingPartly();
//        $this->addNewOrderStatusShippingFully();
    }

    protected function addNewOrderStatusInvoicePartly()
    {
        /** @var StatusResource $statusResource */
        $statusResource = $this->statusResourceFactory->create();
        /** @var Status $status */
        $status = $this->statusFactory->create();
        $status->setData([
            'status' => self::ORDER_STATUS_PROCESSING_INVOICE_PARTLY_CODE,
            'label' => self::ORDER_STATUS_PROCESSING_INVOICE_PARTLY_LABEL,
        ]);
        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        $status->assignState(Order::STATE_PROCESSING, false, true);
    }

    protected function addNewOrderStatusInvoiceFully()
    {
        /** @var StatusResource $statusResource */
        $statusResource = $this->statusResourceFactory->create();
        /** @var Status $status */
        $status = $this->statusFactory->create();
        $status->setData([
            'status' => self::ORDER_STATUS_PROCESSING_INVOICE_FULLY_CODE,
            'label' => self::ORDER_STATUS_PROCESSING_INVOICE_FULLY_LABEL,
        ]);
        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        $status->assignState(Order::STATE_PROCESSING, false, true);
    }

    protected function addNewOrderStatusCreditmemoPartly()
    {
        /** @var StatusResource $statusResource */
        $statusResource = $this->statusResourceFactory->create();
        /** @var Status $status */
        $status = $this->statusFactory->create();
        $status->setData([
            'status' => self::ORDER_STATUS_PROCESSING_CREDITMEMO_PARTLY_CODE,
            'label' => self::ORDER_STATUS_PROCESSING_CREDITMEMO_PARTLY_LABEL,
        ]);
        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        $status->assignState(Order::STATE_PROCESSING, false, true);
    }

    protected function addNewOrderStatusCreditmemoFully()
    {
        /** @var StatusResource $statusResource */
        $statusResource = $this->statusResourceFactory->create();
        /** @var Status $status */
        $status = $this->statusFactory->create();
        $status->setData([
            'status' => self::ORDER_STATUS_PROCESSING_CREDITMEMO_FULLY_CODE,
            'label' => self::ORDER_STATUS_PROCESSING_CREDITMEMO_FULLY_LABEL,
        ]);
        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        $status->assignState(Order::STATE_PROCESSING, false, true);
    }

    protected function addNewOrderStatusShippingPartly()
    {
        /** @var StatusResource $statusResource */
        $statusResource = $this->statusResourceFactory->create();
        /** @var Status $status */
        $status = $this->statusFactory->create();
        $status->setData([
            'status' => self::ORDER_STATUS_PROCESSING_SHIPPING_PARTLY_CODE,
            'label' => self::ORDER_STATUS_PROCESSING_SHIPPING_PARTLY_LABEL,
        ]);
        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        $status->assignState(Order::STATE_PROCESSING, false, true);
    }

    protected function addNewOrderStatusShippingFully()
    {
        /** @var StatusResource $statusResource */
        $statusResource = $this->statusResourceFactory->create();
        /** @var Status $status */
        $status = $this->statusFactory->create();
        $status->setData([
            'status' => self::ORDER_STATUS_PROCESSING_SHIPPING_FULLY_CODE,
            'label' => self::ORDER_STATUS_PROCESSING_SHIPPING_FULLY_LABEL,
        ]);
        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        $status->assignState(Order::STATE_PROCESSING, false, true);
    }

}
