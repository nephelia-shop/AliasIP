<?php
namespace fenomeno\AliasIP\Commands;

use fenomeno\AliasIP\libs\CortexPE\Commando\args\RawStringArgument;
use fenomeno\AliasIP\libs\CortexPE\Commando\BaseCommand;
use fenomeno\AliasIP\Main;
use fenomeno\AliasIP\Sessions\AliasSession;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;

class AliasCommand extends BaseCommand {

    public function __construct()
    {
        parent::__construct(Main::getInstance(), "aliasip");
        $this->setPermission($this->getPermission());
    }


    /** @throws */
    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("target"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $targetName = $args['target'];
        if (($target = $sender->getServer()->getPlayerByPrefix($targetName)) instanceof Player){
            $session = AliasSession::get($target);
            $sender->sendMessage("§e'{$target->getName()}' §7ips: §e" . implode(", ", $session->getIps()));
        } else {
            $sender->sendMessage("§c$targetName n'est pas connecté");
        }
    }

    public function getPermission() : string
    {
        return DefaultPermissions::ROOT_OPERATOR;
    }
}