<?php
namespace fenomeno\AliasIP\Database;

use fenomeno\AliasIP\libs\poggit\libasynql\DataConnector;
use fenomeno\AliasIP\Main;

abstract class AsynqlModel {

    protected DataConnector $database;

    public function __construct()
    {
        $this->database = Main::getInstance()->getDatabaseManager()->getDatabase();
    }

}