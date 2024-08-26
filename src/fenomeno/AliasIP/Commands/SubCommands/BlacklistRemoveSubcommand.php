<?php

namespace fenomeno\AliasIP\Commands\SubCommands;

use fenomeno\AliasIP\AliasUtils;
use fenomeno\AliasIP\Database\Models\Blacklist;
use fenomeno\AliasIP\libs\CortexPE\Commando\args\RawStringArgument;
use fenomeno\AliasIP\libs\CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;

class BlacklistRemoveSubcommand extends BaseSubCommand
{

    public function __construct()
    {
        parent::__construct("remove", "Enlever une ip");
    }

    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument('ip'));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $ip = $args['ip'];
        if(!AliasUtils::isIp($ip)){
            $sender->sendMessage("§c'$ip' n'est pas une ip valide");
            return;
        }
        if (! Blacklist::getInstance()->exists($ip)){
            $sender->sendMessage("§cCet ip n'est pas blacklist");
            return;
        }

        Blacklist::getInstance()->remove($ip);
        $sender->sendMessage("§al'ip $ip' a été enlevé de la blacklist");
    }
}