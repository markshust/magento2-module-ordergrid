<?php

namespace MarkShust\OrderGrid\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Backend\Model\Auth\Session;

class SalesOrderPlaceAfter implements ObserverInterface
{
    /**
     * @var Session
     */
    protected $authSession;

    /**
     * @param Session $authSession
     */
    public function __construct(Session $authSession)
    {
        $this->authSession = $authSession;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            if(empty($order->getRemoteIp())) {
                $adminName = $this->getCurrentUser()->getUsername();
                $order->setData('created_by', $adminName);
                $order->save();
            }
        } catch (\Exception $e) {}
    }

    /**
     * @return \Magento\User\Model\User|null
     */
    private function getCurrentUser()
    {
        return $this->authSession->getUser();
    }
}
