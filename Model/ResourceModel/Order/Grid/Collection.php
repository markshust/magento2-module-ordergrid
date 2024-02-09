<?php declare(strict_types=1);

namespace MarkShust\OrderGrid\Model\ResourceModel\Order\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OrderGridCollection;
use Zend_Db_Expr;

/**
 * Class Collection
 * @package MarkShust\OrderGrid\Model\ResourceModel\Order\Grid
 */
class Collection extends OrderGridCollection
{
    /**
     * Add field to filter.
     *
     * @param string|array $field
     * @param string|int|array|null $condition
     * @return Collection
     */
    public function addFieldToFilter($field, $condition = null): Collection
    {
        if ($field === 'order_items' && !$this->getFlag('product_filter_added')) {
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

            return $this;
        } else {
            return parent::addFieldToFilter($field, $condition);
        }
    }

    /**
     * Perform operations after collection load.
     *
     * @return SearchResult
     */
    protected function _afterLoad(): SearchResult
    {
        $items = $this->getColumnValues('entity_id');

        if (count($items)) {
            $connection = $this->getConnection();

            // Fetch products data separately
            $select = $connection->select()
                ->from([
                    'sales_order_item' => $this->getTable('sales_order_item'),
                ], [
                    'order_id',
                    'sku',
                    'name',
                    'qty_ordered',
                ])
                ->where('order_id IN (?)', $items)
                ->where('parent_item_id IS NULL'); // Eliminate configurable products, otherwise two products show

            $productData = $connection->fetchAll($select);

            // Aggregate products data
            $productsByOrderId = [];
            foreach ($productData as $product) {
                $productsByOrderId[$product['order_id']][] = $product;
            }

            // Loop through orders and aggregate products
            foreach ($items as $orderId) {
                if (isset($productsByOrderId[$orderId])) {
                    $row = $this->getItemById($orderId);
                    $html = '';
                    foreach ($productsByOrderId[$orderId] as $product) {
                        $html .= sprintf('<div>%d x [%s] %s </div>', $product['qty_ordered'], $product['sku'], $product['name']);
                    }
                    $row->setData('order_items', $html);
                }
            }
        }

        return parent::_afterLoad();
    }
}
