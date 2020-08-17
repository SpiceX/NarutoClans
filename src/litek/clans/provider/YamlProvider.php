<?php /** @noinspection MkdirRaceConditionInspection */
/**
 * Copyright 2018-2020 LiTEK
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
declare(strict_types=1);

namespace litek\clans\provider;

use litek\clans\NarutoClans;
use pocketmine\utils\Config;

/**
 * Class YamlDataProvider
 * @package litek\clans\provider
 */
class YamlProvider
{

    /** @var NarutoClans $plugin */
    private $plugin;
    /** @var Config */
    private $clansConfig;
    /** @var Config */
    private $playersConfig;

    /**
     * YamlDataProvider constructor.
     * @param NarutoClans $plugin
     */
    public function __construct(NarutoClans $plugin)
    {
        $this->plugin = $plugin;
        $this->init();
    }

    public function init(): void
    {
        $this->plugin->saveResource("clans.yml");
        $this->clansConfig = new Config($this->getDataFolder() . 'clans.yml', Config::YAML);
        $this->playersConfig = new Config($this->getDataFolder() . 'players.yml', Config::YAML);
    }

    /**
     * @return string $dataFolder
     */
    private function getDataFolder(): string
    {
        return $this->plugin->getDataFolder();
    }

    /**
     * @return Config
     */
    public function getClansConfig(): Config
    {
        return $this->clansConfig;
    }

    /**
     * @return Config
     */
    public function getPlayersConfig(): Config
    {
        return $this->playersConfig;
    }
}