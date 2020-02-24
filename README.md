# magento2 custom module

A simple module for magento2. It's purpose is to trigger an event when an order is made.

- The module is working by implementing the Observer Interface.

- When an order is made, OrderObserver is triggered.

- Copy DimV folder at your <magento2>/app/code directory.

- Navigate to your <magento2> directory with command line and run php bin/magento setup:upgrade to register the module.

- Go to your store and make an order. If everything goes as planned, the required data should be posted to <url> using cURL.
