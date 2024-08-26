<?php
namespace fenomeno\AliasIP;

use fenomeno\AliasIP\Commands\AliasAccountsCommand;
use fenomeno\AliasIP\Commands\AliasCommand;
use fenomeno\AliasIP\Commands\BlacklistCommand;
use fenomeno\AliasIP\Database\DatabaseManager;
use fenomeno\AliasIP\libs\CortexPE\Commando\PacketHooker;
use fenomeno\AliasIP\Listeners\AliasIpListeners;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase {
    use SingletonTrait;

    private DatabaseManager $databaseManager;

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    /** @throws */
    protected function onEnable(): void
    {
        PacketHooker::register($this);

        $this->databaseManager = new DatabaseManager($this);

        $this->getServer()->getPluginManager()->registerEvents(new AliasIpListeners(), $this);

        $this->getServer()->getCommandMap()->registerAll('aliasip', [
            new AliasCommand(),
            new AliasAccountsCommand(),
            new BlacklistCommand()
        ]);
    }

    public function getDatabaseManager(): DatabaseManager
    {
        return $this->databaseManager;
    }

}