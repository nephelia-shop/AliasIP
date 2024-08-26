<?php
namespace fenomeno\AliasIP\Database\Models;

use fenomeno\AliasIP\Database\AsynqlModel;
use fenomeno\AliasIP\libs\poggit\libasynql\SqlError;
use fenomeno\AliasIP\libs\SOFe\AwaitGenerator\Await;
use fenomeno\AliasIP\Main;
use fenomeno\AliasIP\Sessions\AliasSession;
use fenomeno\AliasIP\Sessions\SessionUserData;
use pocketmine\promise\Promise;
use pocketmine\promise\PromiseResolver;

class Ips extends AsynqlModel
{

    public function getUserData(AliasSession $session) : Promise
    {
        $dataPromiseResolver = new PromiseResolver();
        $this->database->executeSelect("aliasip.get", [
            'name' => $session->player->getName()
        ], function (array $rows) use ($dataPromiseResolver) {
            if (isset($rows[0])){
                $dataPromiseResolver->resolve(SessionUserData::jsonDeserialize($rows[0]));
            } else {
                $dataPromiseResolver->resolve(null);
            }
        }, function (SqlError $result) use ($dataPromiseResolver) {
            Main::getInstance()->getLogger()->emergency($result->getQuery() . ' - ' . $result->getErrorMessage());
            $dataPromiseResolver->reject();
        });
        return $dataPromiseResolver->getPromise();
    }

    public function updateIps(AliasSession $session) : void
    {
        Await::f2c(function () use ($session): \Generator {
            yield from $this->database->asyncChange("aliasip.updateIps", (new SessionUserData(
                $session->player->getName(),
                $session->getIps()
            ))->jsonSerialize());
        });
    }

    public function getAllIps(?\Closure $closure = null) : \Generator {
        $rows = yield from $this->database->asyncSelect("aliasip.getAllIps");

        $ips = [];
        foreach($rows as $row) {
            $decodedIps = json_decode($row['ips']);
            $ips[$row['name']] = $decodedIps;
        }

        $closure($ips);
    }

    public function auth(AliasSession $session) : void
    {
        $this->database->executeInsert("aliasip.auth", (new SessionUserData(
            $session->player->getName(),
            [$session->player->getNetworkSession()->getIp()]
        ))->jsonSerialize(), function () use ($session) {
            $session->loadUserData();
        }, function () use ($session) {
            $session->player->kick("§Votre connexion est instable, essayez de vous reconnecter");
        });
    }

}