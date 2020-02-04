<?php

namespace DimV\DigitalUpModule\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderObserver implements ObserverInterface
{

    protected $connector;
    public function __construct()
    {
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // get all the required data from the observer for later use
        $order = $observer->getEvent()->getOrder();
        $email = $order->getCustomerEmail();
        $paymentMethod = $order->getPayment()->getMethodInstance()->getTitle();
        $shippingMethod = $order->getShippingMethod();
        $items = $order->getAllVisibleItems();
        foreach ($items as $item) {
            $products = array($item->getSku() => $item->getPrice());
        }
        $subTotal = $order->getSubTotal();
        $grandTotal = $order->getGrandTotal();
        $shippingCost = $grandTotal - $subTotal;

        // construct the array with the required data
        $myArr = array(
            "email" => $email, "payment_method" => $paymentMethod, "shipping_method" => $shippingMethod, "products" => $products,
            "subtotal" => $subTotal, "shipping_costs" => $shippingCost, "grandtotal" => $grandTotal,
        );

        // encode the array to JSON
        $myJSON = json_encode($myArr);

        // use curl to post JSON data to the endpoint
        $url = "http://smart-digital.gr/test/dimitris-verakis.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myJSON);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        curl_close($ch);

        // ======this is for debugging purposes, you can ignore it===== //
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/templog.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('Email: ' . $email . ' Payment Method: ' . $paymentMethod);
    }
}
