<?php
namespace fenomeno\AliasIP\Commands;

use fenomeno\AliasIP\AliasUtils;
use fenomeno\AliasIP\Database\Models\Ips;
use fenomeno\AliasIP\libs\CortexPE\Commando\args\RawStringArgument;
use fenomeno\AliasIP\libs\CortexPE\Commando\BaseCommand;
use fenomeno\AliasIP\libs\SOFe\AwaitGenerator\Await;
use fenomeno\AliasIP\Main;
use fenomeno\AliasIP\Sessions\AliasSession;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;

class AliasAccountsCommand extends BaseCommand {

    public function __construct()
    {
        parent::__construct(Main::getInstance(), "aliasipaccount");
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
        if (! ($target = $sender->getServer()->getPlayerByPrefix($targetName)) instanceof Player){
            $sender->sendMessage("§c$targetName n'est pas connecté");
            return;
        }
        Await::g2c(Main::getInstance()->getDatabaseManager()->getIps()->getAllIps(function (array $ips) use ($sender, $target){
            $session = AliasSession::get($target);
            $targetIps = $session->getIps();
            $players = AliasUtils::findPlayersByIps($ips, $targetIps, $target->getName());
            if (empty($players) or $players === []){
                $sender->sendMessage("§cAucun compte relié à {$target->getName()}");
            }

            $sender->sendMessage("§7Comptes reliés à §e'{$target->getName()}'§7: §e'" . implode(", ", $players) . "'");
        }));
    }

    public function getPermission() : string
    {
        return DefaultPermissions::ROOT_OPERATOR;
    }

}