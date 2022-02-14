<?php

namespace Commercers\OrdersPartly\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('commercers_invoicestatus')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('commercers_invoicestatus')
            )
                ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'ID'
                )->addColumn(
                    'order_id',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable => false'],
                    'Order ID'
                )->addColumn(
                    'invoice_status',
                    Table::TYPE_TEXT,
                    '255',
                    ['nullable => false'],
                    'Invoice Status'
                )->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'

            )->setComment('Commercers Invoice Status Table');
            $installer->getConnection()->createTable($table);
        }
        if (!$installer->tableExists('commercers_shippingstatus')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('commercers_shippingstatus')
            )
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true,
                    ],
                    'ID'
                )->addColumn(
                    'order_id',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable => false'],
                    'Order ID'
                )->addColumn(
                    'shipping_status',
                    Table::TYPE_TEXT,
                    '255',
                    ['nullable => false'],
                    'Shipping Status'
                )->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )->setComment('Commercers Shipping Status Table');
            $installer->getConnection()->createTable($table);
        }
        if (!$installer->tableExists('commercers_creditmemostatus')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('commercers_creditmemostatus')
            )
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true,
                    ],
                    'ID'
                )->addColumn(
                    'order_id',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable => false'],
                    'Order ID'
                )->addColumn(
                    'creditmemo_status',
                    Table::TYPE_TEXT,
                    '255',
                    ['nullable => false'],
                    'Creditmemo Status'
                )->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )->setComment('Commercers Creditmemo Status Table');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
