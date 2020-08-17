<?php
/**
 * Copyright 2018-2020 LiTEK - Josewowgame2888
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

namespace litek\clans\command\types;

use litek\clans\NarutoClans;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class ClanCommand extends Command implements PluginIdentifiableCommand
{

    /**
     * @var NarutoClans
     */
    private $plugin;

    public function __construct(NarutoClans $plugin)
    {
        parent::__construct("clan", "comando do usuario", "§l§c» §r§7/cla", ["clan", "cla"]);
        $this->plugin = $plugin;
    }

    /**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args): void
	{
		if ($sender instanceof Player) {
            $this->plugin->getFormManager()->sendClanListPanel($sender);
		}
	}

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}