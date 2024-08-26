<?php
namespace fenomeno\AliasIP\Commands\SubCommands;

use fenomeno\AliasIP\AliasUtils;
use fenomeno\AliasIP\Database\Models\Blacklist;
use fenomeno\AliasIP\libs\CortexPE\Commando\args\RawStringArgument;
use fenomeno\AliasIP\libs\CortexPE\Commando\args\TextArgument;
use fenomeno\AliasIP\libs\CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;

class BlacklistAddSubcommand extends BaseSubCommand {

    public function __construct()
    {
        parent::__construct("add", "Ajouter une ip");
    }

    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("ip"));
        $this->registerArgument(1, new TextArgument("raison", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $ip = $args['ip'];
        if(!AliasUtils::isIp($ip)){
            $sender->sendMessage("§c'$ip' n'est pas une ip valide");
            return;
        }
        if(Blacklist::getInstance()->exists($ip)){
            $sender->sendMessage("L'ip '$ip' est déjà blacklist");
            return;
        }
        $raison = $args['raison'] ?? "Raison non spécifiée";
        Blacklist::getInstance()->addIp($ip, $raison);
        $sender->sendMessage("§aL'ip '$ip' a été ajouté à la blacklist");
    }
}