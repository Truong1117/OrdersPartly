<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Commercers\OrdersPartly\Model;

class ShippingStatus extends \Magento\Framework\Model\AbstractModel {

    protected function _construct() {
        $this->_init('Commercers\OrdersPartly\Model\ResourceModel\ShippingStatus');
    }
}
