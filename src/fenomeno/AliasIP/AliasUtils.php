<?php
namespace fenomeno\AliasIP;

class AliasUtils {

    public static function findPlayersByIps(array $allIps, array $searchIps, string $targetName): array {
        $players = [];
        foreach ($allIps as $player => $ips) {
            foreach ($searchIps as $searchIp) {
                if (in_array($searchIp, $ips)) {
                    if($player === $targetName){
                        continue;
                    }
                    $players[] = $player;
                    break;
                }
            }
        }
        return $players;
    }

    public static function isIp(string $ip) : bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

}