<?php

namespace fenomeno\AliasIP\Commands\SubCommands;

use fenomeno\AliasIP\Database\Models\Blacklist;
use fenomeno\AliasIP\libs\CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;

class BlacklistListAddSubcommand extends BaseSubCommand
{

    public function __construct()
    {
        parent::__construct("list", "Affiche la liste des ip");
    }

    protected function prepare(): void
    {
        // TODO: Implement prepare() method.
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if ((empty(Blacklist::getInstance()->getIps())) or Blacklist::getInstance()->getIps() === []){
            $sender->sendMessage("§cAucune ip blacklist");
            return;
        }

        foreach (Blacklist::getInstance()->getIps() as $ipEntry){
            $sender->sendMessage("§7Ip §e'$ipEntry->ip' §7pour: §e'$ipEntry->raison' §7le §e'$ipEntry->date'");
        }

    }

}