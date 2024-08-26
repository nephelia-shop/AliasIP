<?php
namespace fenomeno\AliasIP\Contracts;

class BlacklistEntry implements \JsonSerializable {

    public function __construct(
        public string $ip,
        public string $raison,
        public string $date
    )
    {
    }

    public static function make(string $ip, string $raison, string $date) : static
    {
        return new self($ip, $raison, $date);
    }

    public static function jsonDeserialize(array $data) : BlacklistEntry {
        return new BlacklistEntry(
            (string)$data['ip'],
            (string)($data['raison'] ?? "raison non spécifiée"),
            (string)$data['date_added']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'ip'       => $this->ip,
            'raison'     => $this->raison,
            'date' => $this->date
        ];
    }
}