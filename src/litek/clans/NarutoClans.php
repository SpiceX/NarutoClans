<?php

namespace litek\clans;

use _64FF00\PurePerms\PurePerms;
use litek\clans\clan\ClanManager;
use litek\clans\command\types\ClanCommand;
use litek\clans\form\FormManager;
use litek\clans\provider\YamlProvider;
use pocketmine\plugin\PluginBase;

class NarutoClans extends PluginBase
{
    /** @var NarutoClans */
    private static $instance;
    /** @var YamlProvider */
    private $yamlProvider;
    /** @var ClanManager */
    private $clanManager;
    /** @var PurePerms|null */
    private $purePerms;
    /** @var FormManager */
    private $formManager;

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->initVariables();
        $this->getServer()->getCommandMap()->register('clan', new ClanCommand($this));
    }

    private function initVariables(): void
    {
        $this->yamlProvider = new YamlProvider($this);
        $this->formManager = new FormManager($this);
        $this->clanManager = new ClanManager($this);
        $this->purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
    }

    /**
     * @return NarutoClans
     */
    public static function getInstance(): NarutoClans
    {
        return self::$instance;
    }

    /**
     * @return YamlProvider
     */
    public function getYamlProvider(): YamlProvider
    {
        return $this->yamlProvider;
    }

    /**
     * @return ClanManager
     */
    public function getClanManager(): ClanManager
    {
        return $this->clanManager;
    }

    /**
     * @return PurePerms|null
     */
    public function getPurePerms(): ?PurePerms
    {
        return $this->purePerms;
    }

    /**
     * @return FormManager
     */
    public function getFormManager(): FormManager
    {
        return $this->formManager;
    }

}