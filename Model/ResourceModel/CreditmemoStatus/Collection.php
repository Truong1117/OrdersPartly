<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Commercers\OrdersPartly\Model\ResourceModel\CreditmemoStatus;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection {
    protected function _construct() {
        $this->_init('Commercers\OrdersPartly\Model\CreditmemoStatus', 'Commercers\OrdersPartly\Model\ResourceModel\CreditmemoStatus');
    }
}
