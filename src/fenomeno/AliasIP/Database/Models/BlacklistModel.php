<?php
namespace fenomeno\AliasIP\Database\Models;

use fenomeno\AliasIP\Contracts\BlacklistEntry;
use fenomeno\AliasIP\Database\AsynqlModel;
use fenomeno\AliasIP\libs\SOFe\AwaitGenerator\Await;

class BlacklistModel extends AsynqlModel {

    public function addEntry(BlacklistEntry $blacklistEntry) : void
    {
        Await::f2c(function () use ($blacklistEntry): \Generator {
            yield from $this->database->asyncInsert("aliasip.blacklist.add", $blacklistEntry->jsonSerialize());
        });
    }

    public function removeEntry(string $ip) : void
    {
        Await::f2c(function () use ($ip): \Generator {
            yield from $this->database->asyncGeneric("aliasip.blacklist.remove", [
                'ip' => $ip
            ]);
        });
    }

    public function getAll(?\Closure $closure = null) : void {
        $this->database->executeSelect("aliasip.blacklist.getAll", [], function (array $rows) use ($closure) {
            $closure($rows);
        });
    }

}
