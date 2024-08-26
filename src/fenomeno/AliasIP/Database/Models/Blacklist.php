<?php
namespace fenomeno\AliasIP\Database\Models;

use fenomeno\AliasIP\Contracts\BlacklistEntry;
use fenomeno\AliasIP\Main;
use pocketmine\utils\SingletonTrait;

class Blacklist {
    use SingletonTrait;

    /** @var BlacklistEntry[] */
    private array $ips = [];

    public function load() : void
    {
        Main::getInstance()->getDatabaseManager()->getBlacklist()->getAll(function (array $entries){
           foreach ($entries as $entry){
               $this->ips[] = BlacklistEntry::jsonDeserialize($entry);
           }
        });
    }

    public function addIp(string $ip, string $raison) : void
    {
        $blacklistEntry = BlacklistEntry::make($ip, $raison, $date = date_format(new \DateTime(), "d-m-Y i:H:s"));
        $this->ips[] = $blacklistEntry;
        Main::getInstance()->getDatabaseManager()->getBlacklist()->addEntry($blacklistEntry);
    }

    public function getIps() : array
    {
        return $this->ips;
    }

    public function remove(string $ip) : void
    {
        unset($this->ips[array_search($this->getBlacklistEntry($ip), $this->ips)]);
        Main::getInstance()->getDatabaseManager()->getBlacklist()->removeEntry($ip);
    }

    public function getBlacklistEntry(string $ip) : ?BlacklistEntry
    {
        $ret = null;
        foreach ($this->ips as $blacklistEntry){
            if($blacklistEntry->ip === $ip){
                $ret = $blacklistEntry;
                break;
            }
        }
        return $ret;
    }

    public function exists(string $ip) : bool
    {
        return Blacklist::getInstance()->getBlacklistEntry($ip) !== null;
    }

}