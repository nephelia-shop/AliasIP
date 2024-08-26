<?php

namespace fenomeno\AliasIP\Listeners;

use fenomeno\AliasIP\Database\Models\Blacklist;
use fenomeno\AliasIP\Sessions\AliasSession;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\permission\DefaultPermissions;

class AliasIpListeners implements Listener {

    /** @var AliasSession[] */
    private array $sessions = [];

    public function __construct()
    {
    }

    public function onLogin(PlayerLoginEvent $event) : void
    {
        $player = $event->getPlayer();
        $this->sessions[$player->getName()] = AliasSession::get($player);
    }

    public function onJoin(PlayerJoinEvent $event) : void
    {
        $session = $this->sessions[($player = $event->getPlayer())->getName()];
        $ip = $player->getNetworkSession()->getIp();
        if (! $session->hasIp($ip)){
            foreach ($player->getServer()->getOnlinePlayers() as $p){
                if($p->hasPermission(DefaultPermissions::ROOT_OPERATOR)){
                    $p->sendMessage("§cWARNING - §e'{$player->getName()}' §7vient de se connecter avec une nouvelle ip §e'$ip'");
                }
            }
            $session->addIp($ip);
        }

        if (Blacklist::getInstance()->exists($ip)){
            $entry = Blacklist::getInstance()->getBlacklistEntry($ip);
            $player->kick("§7Votre ip est blacklist\n\nRaison : §e'$entry->raison'\n\n§7Date :§e'$entry->date'");
        }

    }

}