<?php
namespace fenomeno\AliasIP\Sessions;

class SessionUserData implements \JsonSerializable {

    public function __construct(
        protected string $name,
        protected array  $ips = []
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getIps(): array
    {
        return $this->ips;
    }

    public static function jsonDeserialize(array $data) : SessionUserData
    {
        return new self(
            (string)$data['name'],
            (array)json_decode($data['ips'], true)
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'ips' => json_encode($this->ips ?? [])
        ];
    }
}