<?php
namespace MarkShust\OrderGrid\Model\ResourceModel\Order\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * Initialize the select statement.
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        // Add the sales_order_item model to this collection
        $this->join(
            [$this->getTable('sales_order_item')],
            "main_table.entity_id = {$this->getTable('sales_order_item')}.order_id",
            []
        );

        // Group by the order id, which is initially what this grid is id'd by
        $this->getSelect()->group('main_table.entity_id');

        return $this;
    }

    /**
     * Add field to filter.
     *
     * @param string|array $field
     * @param string|int|array|null $condition
     * @return SearchResult
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'products' && !$this->getFlag('product_filter_added')) {
            // Add the sales/order_item model to this collection
            $this->getSelect()->join(
                [$this->getTable('sales_order_item')],
                "main_table.entity_id = {$this->getTable('sales_order_item')}.order_id",
                []
            );

            // Group by the order id, which is initially what this grid is id'd by
            $this->getSelect()->group('main_table.entity_id');

            // On the products field, let's add the sku and name as filterable fields
            $this->addFieldToFilter([
                "{$this->getTable('sales_order_item')}.sku",
                "{$this->getTable('sales_order_item')}.name",
            ], [
                $condition,
                $condition,
            ]);

            $this->setFlag('product_filter_added', 1);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Perform operations after collection load.
     *
     * @return SearchResult
     */
    protected function _afterLoad()
    {
        $items = $this->getColumnValues('entity_id');

        if (count($items)) {
            $connection = $this->getConnection();

            // Build out item sql to add products to the order data
            $select = $connection->select()
                ->from([
                    'sales_order_item' => $this->getTable('sales_order_item'),
                ], [
                    'order_id',
                    'product_skus'  => new \Zend_Db_Expr('GROUP_CONCAT(`sales_order_item`.sku SEPARATOR "|")'),
                    'product_names' => new \Zend_Db_Expr('GROUP_CONCAT(`sales_order_item`.name SEPARATOR "|")'),
                    'product_qtys'  => new \Zend_Db_Expr('GROUP_CONCAT(`sales_order_item`.qty_ordered SEPARATOR "|")'),
                ])
                ->where('order_id IN (?)', $items)
                ->where('parent_item_id IS NULL') // Eliminate configurable products, otherwise two products show
                ->group('order_id');

            $itemCollection = $connection->fetchAll($select);

            // Loop through this sql an add items to related orders
            foreach ($itemCollection as $item) {
                $row = $this->getItemById($item['order_id']);
                $productSkus = explode('|', $item['product_skus']);
                $productQtys = explode('|', $item['product_qtys']);
                $productNames = explode('|', $item['product_names']);
                $html = '';

                foreach ($productSkus as $index => $sku) {
                    $html .= sprintf('<div>%d x [%s] %s </div>', $productQtys[$index], $sku, $productNames[$index]);
                }

                $row->setOrderItems($html);
            }
        }

        return parent::_afterLoad();
    }

    /**
     * Get the record count.
     *
     * @return int
     */
    public function getSize()
    {
        if ($this->_totalRecords === NULL) {
            $sql = $this->getSelectCountSql();
            $this->_totalRecords = count($this->getConnection()->fetchAll($sql, $this->_bindParams));
        }

        return intval($this->_totalRecords);
    }
}
