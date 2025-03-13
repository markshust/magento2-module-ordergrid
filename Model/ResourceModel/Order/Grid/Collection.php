<?php declare(strict_types=1);

namespace MarkShust\OrderGrid\Model\ResourceModel\Order\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OrderGridCollection;

class Collection extends OrderGridCollection
{
    private const ORDER_ITEMS_FIELD = 'order_items';
    private const PRODUCT_FILTER_FLAG = 'product_filter_added';
    private const SALES_ORDER_ITEM_TABLE = 'sales_order_item';

    /**
     * Add field to filter with special handling for order items.
     *
     * @param string|array $field
     * @param string|int|array|null $condition
     * @return Collection
     */
    public function addFieldToFilter(
        $field,
        $condition = null
    ): Collection {
        // Handle special case for order_items field
        if ($field === self::ORDER_ITEMS_FIELD && !$this->getFlag(self::PRODUCT_FILTER_FLAG)) {
            return $this->addProductFilter($condition);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Add product-specific filtering to collection.
     *
     * @param array|int|string|null $condition
     * @return Collection
     */
    private function addProductFilter(
        array|int|string|null $condition
    ): Collection {
        $orderItemTable = $this->getTable(self::SALES_ORDER_ITEM_TABLE);
        $orderItemAlias = 'soi';

        // Join the order item table
        $this->getSelect()->join(
            [$orderItemAlias => $orderItemTable],
            "main_table.entity_id = {$orderItemAlias}.order_id",
            []
        );

        // Group by order ID to avoid duplicates
        $this->getSelect()->group('main_table.entity_id');

        // Filter by product SKU and name
        $this->addFieldToFilter(
            [
                "{$orderItemAlias}.sku",
                "{$orderItemAlias}.name",
            ],
            [
                $condition,
                $condition,
            ]
        );

        $this->setFlag(self::PRODUCT_FILTER_FLAG, 1);

        return $this;
    }

    /**
     * Add order items data to collection after load.
     *
     * @return SearchResult
     */
    protected function _afterLoad(): SearchResult
    {
        $orderIds = $this->getColumnValues('entity_id');

        if (!empty($orderIds)) {
            $this->addOrderItemsToCollection($orderIds);
        }

        return parent::_afterLoad();
    }

    /**
     * Add order items HTML to each order in the collection.
     *
     * @param array $orderIds
     * @return void
     */
    private function addOrderItemsToCollection(
        array $orderIds
    ): void {
        // Get all relevant order items
        $orderItems = $this->getOrderItems($orderIds);

        // Group order items by order ID for efficient lookup
        $productsByOrderId = $this->groupOrderItemsByOrderId($orderItems);

        // Add HTML representation to each order
        foreach ($orderIds as $orderId) {
            if (!isset($productsByOrderId[$orderId])) {
                continue;
            }

            $order = $this->getItemById($orderId);
            if (!$order) {
                continue;
            }

            $order->setData(self::ORDER_ITEMS_FIELD, $this->formatOrderItemsHtml($productsByOrderId[$orderId]));
        }
    }

    /**
     * Group order items by their parent order ID.
     *
     * @param array $orderItems
     * @return array
     */
    private function groupOrderItemsByOrderId(
        array $orderItems
    ): array {
        $result = [];

        foreach ($orderItems as $item) {
            $result[$item['order_id']][] = $item;
        }

        return $result;
    }

    /**
     * Format order items as HTML.
     *
     * @param array $orderItems
     * @return string
     */
    private function formatOrderItemsHtml(
        array $orderItems
    ): string {
        $html = '';

        foreach ($orderItems as $item) {
            $html .= sprintf(
                '<div>%d x [%s] %s</div>',
                (int)$item['qty_ordered'],
                $item['sku'],
                $item['name']
            );
        }

        return $html;
    }

    /**
     * Get order items for the specified order IDs.
     *
     * @param array $orderIds
     * @return array
     */
    private function getOrderItems(
        array $orderIds
    ): array {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(
                $this->getTable(self::SALES_ORDER_ITEM_TABLE),
                [
                    'order_id',
                    'sku',
                    'name',
                    'qty_ordered',
                ]
            )
            ->where('order_id IN (?)', $orderIds)
            ->where('parent_item_id IS NULL'); // Exclude child products (e.g., in configurable products)

        return $connection->fetchAll($select);
    }
}
