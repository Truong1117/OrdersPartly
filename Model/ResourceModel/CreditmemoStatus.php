<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Commercers\OrdersPartly\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CreditmemoStatus extends AbstractDb {
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('commercers_creditmemostatus', 'id');
    }
}
