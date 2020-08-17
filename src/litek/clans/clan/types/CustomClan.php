<?php

namespace litek\clans\clan\types;

use _64FF00\PurePerms\data\UserDataManager;
use litek\clans\clan\Clan;
use litek\clans\NarutoClans;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\Player;

class CustomClan implements Clan
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $joinPerm;
    /**
     * @var array
     */
    private $perms;
    /**
     * @var array
     */
    private $players;

    /**
     * CustomClan constructor.
     * @param string $name
     * @param string $joinPerm
     * @param array $perms
     * @param array $players
     */
    public function __construct(string $name, string $joinPerm, array $perms, array $players = [])
    {
        $this->name = $name;
        $this->joinPerm = $joinPerm;
        $this->perms = $perms;
        $this->players = $players;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getJoinPerm(): string
    {
        return $this->joinPerm;
    }

    /**
     * @return array
     */
    public function getPerms(): array
    {
        return $this->perms;
    }

    public function join(Player $player): void
    {
        if (in_array($player->getName(), $this->players, true)){
            $player->sendMessage("§l§c» §r§7Você já pertence a este clã.");
            return;
        }
        if ($this->getPlugin()->getClanManager()->isInClan($player)){
            $player->sendMessage("§l§c» §r§7Você já pertence a um clã.");
            return;
        }
        $this->players[] = $player->getName();
        foreach ($this->getPerms() as $perm) {
            $this->getPermissionManager()->setPermission($player, $perm);
        }
        $player->broadcastEntityEvent(ActorEventPacket::CONSUME_TOTEM);
        $player->broadcastEntityEvent(ActorEventPacket::FIREWORK_PARTICLES);
        $player->sendTitle("§a{$this->name}");
        $player->sendMessage("§l§a» §r§7Você se juntou ao clã §a{$this->name}.");
    }

    public function leave(Player $player): void
    {
        if (in_array($player->getName(), $this->players, true)) {
            unset($this->players[array_search($player->getName(), $this->players, true)]);
        }
        foreach ($this->getPerms() as $perm) {
            $this->getPermissionManager()->unsetPermission($player, $perm);
        }
        $player->sendMessage("§l§a» §r§7Você deixou o clã §a{$this->name}.");
    }

    public function save(): void
    {
        $config = $this->getPlugin()->getYamlProvider()->getClansConfig();
        $playersConfig = $this->getPlugin()->getYamlProvider()->getPlayersConfig();
        $config->set($this->name, ['perm' => $this->joinPerm, 'perms' => $this->perms]);
        $playersConfig->set($this->name, $this->players);
        $config->save();
        $playersConfig->save();
    }

    public function getPermissionManager(): UserDataManager
    {
        return $this->getPlugin()->getPurePerms()->getUserDataMgr();
    }

    /**
     * @return NarutoClans
     */
    public function getPlugin(): NarutoClans
    {
        return NarutoClans::getInstance();
    }

    /**
     * @return array
     */
    public function getPlayers(): array
    {
        return $this->players;
    }
}