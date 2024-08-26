<?php
namespace fenomeno\AliasIP\Commands;

use fenomeno\AliasIP\Commands\SubCommands\BlacklistAddSubcommand;
use fenomeno\AliasIP\Commands\SubCommands\BlacklistListAddSubcommand;
use fenomeno\AliasIP\Commands\SubCommands\BlacklistRemoveSubcommand;
use fenomeno\AliasIP\libs\CortexPE\Commando\BaseCommand;
use fenomeno\AliasIP\Main;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;

class BlacklistCommand extends BaseCommand {

    public function __construct()
    {
        parent::__construct(Main::getInstance(), "blacklist", "Blacklister une ip");
        $this->setPermission($this->getPermission());
    }

    protected function prepare(): void
    {
        $this->registerSubCommand(new BlacklistAddSubcommand());
        $this->registerSubCommand(new BlacklistRemoveSubcommand());
        $this->registerSubCommand(new BlacklistListAddSubcommand());
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sender->sendMessage("/blacklist add/remove/list");
    }

    public function getPermission() : string{
        return DefaultPermissions::ROOT_OPERATOR;
    }

}