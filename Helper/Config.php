<?php

namespace Commercers\OrdersPartly\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper {
    const XML_PATH_ENABLED = 'orderspartly/general/config';

    const XML_PATH_ORDER_STATUS = 'orderspartly/order_status/order_statuses';

    const XML_PATH_INVOICE_STATUS_PARTLY = 'orderspartly/order_status/invoice_partly';

    const XML_PATH_SHIPPING_STATUS_PARTLY = 'orderspartly/order_status/shipping_partly';

    const XML_PATH_CREDIT_STATUS_PARTLY = 'orderspartly/order_status/creditmemo_partly';

    const XML_PATH_DAY_BACK = 'orderspartly/order_status/days_back';

    const XML_PATH_CRON_RUN = 'orderspartly/order_status/cron';

    protected $_storeManager;

    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManagerInterface;
    }
    public function getConfigValue($path,$storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isEnabled($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_ENABLED,$storeId);
    }
    public function getOrderStatus($storeId = null){
        return $this->getConfigValue(self::XML_PATH_ORDER_STATUS, $storeId);
    }
    public function getInvoiceStatusPartly($storeId = null){
        return $this->getConfigValue(self::XML_PATH_INVOICE_STATUS_PARTLY, $storeId);
    }
    public function getCreditStatusPartly($storeId = null){
        return $this->getConfigValue(self::XML_PATH_CREDIT_STATUS_PARTLY, $storeId);
    }
    public function getShippingStatusPartly($storeId = null){
        return $this->getConfigValue(self::XML_PATH_SHIPPING_STATUS_PARTLY, $storeId);
    }
    public function getDayBack($storeId = null){
        return $this->getConfigValue(self::XML_PATH_DAY_BACK, $storeId);
    }

}
