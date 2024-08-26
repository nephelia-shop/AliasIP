<?php
namespace fenomeno\AliasIP\Database;

use fenomeno\AliasIP\Database\Models\Blacklist;
use fenomeno\AliasIP\Database\Models\BlacklistModel;
use fenomeno\AliasIP\Database\Models\Ips;
use fenomeno\AliasIP\libs\poggit\libasynql\DataConnector;
use fenomeno\AliasIP\libs\poggit\libasynql\libasynql;
use fenomeno\AliasIP\Main;
use pocketmine\scheduler\ClosureTask;

class DatabaseManager {

    private DataConnector $database;

    public function __construct(Main $main)
    {
        $main->saveDefaultConfig();
        $this->database = libasynql::create($main, $main->getConfig()->get("database"), [
            "sqlite" => "sqlite.sql",
            "mysql" => "mysql.sql"
        ]);

        $this->database->executeGeneric("aliasip.init");
        $this->database->executeGeneric("aliasip.blacklist.init");
        $this->database->waitAll();

        $main->getScheduler()->scheduleDelayedTask(new ClosureTask(function (){
            Blacklist::getInstance()->load();
        }), 20);
    }

    public function getIps(): Ips
    {
        return new Ips();
    }

    public function getBlacklist(): BlacklistModel
    {
        return new BlacklistModel();
    }

    public function getDatabase(): DataConnector
    {
        return $this->database;
    }

}