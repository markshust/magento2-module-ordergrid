<?php declare(strict_types=1);

namespace MarkShust\OrderGrid\Plugin;

use Magento\Framework\Api\Search\DocumentInterface;
use Magento\Ui\Model\Export\MetadataProvider;

class FormatOrderItemsExport
{
    /**
     * Place order_items data into sanitized, semicolon-delimited list for order export.
     * @param MetadataProvider $subject
     * @param DocumentInterface $document
     * @param $fields
     * @param $options
     * @return array
     */
    public function beforeGetRowData(
        MetadataProvider $subject,
        DocumentInterface $document,
        $fields,
        &$options
    ): array
    {
        if ($orderItems = $document->getData('order_items')) {
            $decodedItems = html_entity_decode($orderItems);
            $explodedItems = explode('</div><div>', $decodedItems);

            foreach ($explodedItems as $itemId => $explodedItem) {
                $explodedItems[$itemId] = trim(strip_tags($explodedItem));
            }

            $implodedItems = implode(';', $explodedItems);
            $document->setData('order_items', $implodedItems);
        }

        return [
            $document,
            $fields,
            $options,
        ];
    }
}
