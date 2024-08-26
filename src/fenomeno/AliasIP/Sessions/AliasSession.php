<?php
namespace fenomeno\AliasIP\Sessions;

use fenomeno\AliasIP\Database\Models\Ips;
use fenomeno\AliasIP\Main;
use pocketmine\player\Player;

class AliasSession {

    private static \WeakMap $map;

    public static function get(Player $player) : static {
        if (! isset(static::$map)){
            static::$map = new \WeakMap();
        }

        return static::$map[$player] ??= static::$map[$player] = (new self($player))->auth();
    }

    private function auth() : static
    {
        $session = $this;
        Main::getInstance()->getDatabaseManager()->getIps()->auth($session);
        return $session;
    }

    public function loadUserData() : void
    {
        Main::getInstance()->getDatabaseManager()->getIps()->getUserData($this)->onCompletion(function (?SessionUserData $userData){
            if($userData !== null){
                $this->setIps($userData->getIps());
            }
        }, fn() => throw new \Error("Impossible de load la session de {$this->player->getName()}"));
    }

    public function __construct(
        public readonly Player $player,
        private array          $ips = []
    )
    {
    }

    public function getIps(): array
    {
        return $this->ips;
    }

    public function hasIp(string $ip) : string
    {
        return in_array($ip, $this->ips);
    }

    public function addIp(string $ip) : void
    {
        $this->ips[] = $ip;
        Main::getInstance()->getDatabaseManager()->getIps()->updateIps($this);
    }

    public function setIps(array $ips): void
    {
        $this->ips = $ips;
    }

}