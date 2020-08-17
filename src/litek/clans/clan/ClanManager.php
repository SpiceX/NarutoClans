<?php

namespace litek\clans\clan;

use litek\clans\clan\types\CustomClan;
use litek\clans\NarutoClans;
use pocketmine\Player;

class ClanManager
{
    /** @var CustomClan[] */
    private $clans = [];
    /** @var NarutoClans */
    private $plugin;

    /**
     * YamlDataProvider constructor.
     * @param NarutoClans $plugin
     */
    public function __construct(NarutoClans $plugin)
    {
        $this->plugin = $plugin;
        if (($count = $this->loadClans()) > 0){
            $plugin->getLogger()->info("§a($count) clãs foram carregados.");
        }
    }

    private function loadClans(): int
    {
        $clanCount = 0;
        $config = $this->plugin->getYamlProvider()->getClansConfig();
        $playersConfig = $this->plugin->getYamlProvider()->getPlayersConfig();
        $clans = $config->getAll();
        foreach ($clans as $clanName => $data) {
            $this->addClan($clanName, $data['perm'], $data['perms'], $playersConfig->getNested($clanName, []));
            $clanCount++;
        }
        return $clanCount;
    }

    public function saveAll(): void
    {
        foreach ($this->clans as $clan) {
            $clan->save();
        }
    }

    public function isInClan(Player $player): bool
    {
        foreach ($this->clans as $clan) {
            foreach ($clan->getPlayers() as $clanPlayer) {
                if ($player->getName() === $clanPlayer){
                    return true;
                }
            }
        }
        return false;
    }

    public function getPlayerClan(Player  $player): ?CustomClan
    {
        foreach ($this->clans as $clan) {
            foreach ($clan->getPlayers() as $clanPlayer) {
                if ($player->getName() === $clanPlayer){
                    return $clan;
                }
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @param string $joinPerm
     * @param array $perms
     * @param array $players
     */
    public function addClan(string $name, string $joinPerm, array $perms, array $players = []): void
    {
        $this->clans[$name] = new CustomClan($name, $joinPerm, $perms, $players);
    }

    /**
     * @param string $name
     */
    public function removeClan(string $name): void
    {
        if (isset($this->clans[$name])){
            unset($this->clans[$name]);
        }
    }

    /**
     * @param string $name
     * @return CustomClan|null
     */
    public function getClan(string $name): ?CustomClan
    {
        return $this->clans[$name] ?? null;
    }

    /**
     * @return NarutoClans
     */
    public function getPlugin(): NarutoClans
    {
        return $this->plugin;
    }

    /**
     * @return CustomClan[]
     */
    public function getClans(): array
    {
        return $this->clans;
    }
}