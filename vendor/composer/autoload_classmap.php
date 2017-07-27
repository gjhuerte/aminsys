<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'BaseController' => $baseDir . '/app/controllers/BaseController.php',
    'CreateItemtypeTable' => $baseDir . '/app/database/migrations/2017_07_21_073935_create_itemtype_table.php',
    'CreateOfficeTable' => $baseDir . '/app/database/migrations/2017_07_21_073456_create_office_table.php',
    'CreateReferencetagTable' => $baseDir . '/app/database/migrations/2017_07_22_175326_create_referencetag_table.php',
    'CreateSupplyTable' => $baseDir . '/app/database/migrations/2017_07_21_073957_create_supply_table.php',
    'CreateSupplyledgerTable' => $baseDir . '/app/database/migrations/2017_07_22_160846_create_supplyledger_table.php',
    'CreateSupplytransactionTable' => $baseDir . '/app/database/migrations/2017_07_21_074024_create_supplytransaction_table.php',
    'CreateUserTable' => $baseDir . '/app/database/migrations/2017_03_03_090731_create_user_table.php',
    'CreateViewTable' => $baseDir . '/app/database/migrations/2017_07_22_070835_create_view_table.php',
    'DatabaseSeeder' => $baseDir . '/app/database/seeds/DatabaseSeeder.php',
    'HomeController' => $baseDir . '/app/controllers/HomeController.php',
    'IlluminateQueueClosure' => $vendorDir . '/laravel/framework/src/Illuminate/Queue/IlluminateQueueClosure.php',
    'ItemType' => $baseDir . '/app/models/ItemType.php',
    'ItemTypeController' => $baseDir . '/app/controllers/ItemTypeController.php',
    'ItemtypeTableSeeder' => $baseDir . '/app/database/seeds/ItemtypeTableSeeder.php',
    'Normalizer' => $vendorDir . '/patchwork/utf8/src/Normalizer.php',
    'Office' => $baseDir . '/app/models/Office.php',
    'OfficeController' => $baseDir . '/app/controllers/OfficeController.php',
    'OfficeTableSeeder' => $baseDir . '/app/database/seeds/OfficeTableSeeder.php',
    'SessionHandlerInterface' => $vendorDir . '/symfony/http-foundation/Resources/stubs/SessionHandlerInterface.php',
    'StockCardController' => $baseDir . '/app/controllers/StockCardController.php',
    'Supply' => $baseDir . '/app/models/Supply.php',
    'SupplyController' => $baseDir . '/app/controllers/SupplyController.php',
    'SupplyInventoryController' => $baseDir . '/app/controllers/SupplyInventoryController.php',
    'SupplyLedger' => $baseDir . '/app/models/SupplyLedger.php',
    'SupplyLedgerController' => $baseDir . '/app/controllers/SupplyLedgerController.php',
    'SupplyTableSeeder' => $baseDir . '/app/database/seeds/SupplyTableSeeder.php',
    'SupplyTransaction' => $baseDir . '/app/models/SupplyTransaction.php',
    'TestCase' => $baseDir . '/app/tests/TestCase.php',
    'User' => $baseDir . '/app/models/User.php',
    'Whoops\\Module' => $vendorDir . '/filp/whoops/src/deprecated/Zend/Module.php',
    'Whoops\\Provider\\Zend\\ExceptionStrategy' => $vendorDir . '/filp/whoops/src/deprecated/Zend/ExceptionStrategy.php',
    'Whoops\\Provider\\Zend\\RouteNotFoundStrategy' => $vendorDir . '/filp/whoops/src/deprecated/Zend/RouteNotFoundStrategy.php',
);
