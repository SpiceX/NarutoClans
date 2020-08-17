<?php

/**
 * Copyright 2020-2022 LiTEK
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

namespace litek\clans\form;

use litek\clans\form\elements\Button;
use litek\clans\form\elements\Image;
use litek\clans\form\types\MenuForm;
use litek\clans\NarutoClans;
use pocketmine\Player;

class FormManager
{
    /**
     * @var NarutoClans
     */
    private $plugin;

    public function __construct(NarutoClans $plugin)
    {
        $this->plugin = $plugin;
    }

    public function sendClanListPanel(Player $player): void
    {
        $clan = $this->plugin->getClanManager()->getPlayerClan($player);
        if ($clan !== null) {
            $clanName = "§a{$clan->getName()}";
        } else {
            $clanName = "";
        }
        $player->sendForm(new MenuForm('§l§a» §r§7Lista de clãs §l§a«', "§7Seu clã: $clanName.", $this->getClanButtons(),
            function (Player $player, Button $selected): void {
                if (!$selected->hasImage()) {
                    $clan = $this->getPlugin()->getClanManager()->getClan($selected->getText());
                    if ($clan !== null) {
                        $clan->join($player);
                    }
                } else {
                    $clan = $this->getPlugin()->getClanManager()->getPlayerClan($player);
                    if ($clan !== null) {
                        $clan->leave($player);
                    } else {
                        $player->sendMessage("§l§c» §r§7Você não pertence a nenhum clã.");
                    }
                }

            }));
    }

    public function getClanButtons(): array
    {
        $buttons = [];
        foreach ($this->plugin->getClanManager()->getClans() as $clan) {
            $buttons[] = new Button($clan->getName());
        }
        $buttons[] = new Button("Leave", new Image("textures/ui/realms_red_x", Image::TYPE_PATH));
        return $buttons;
    }

    /**
     * @return NarutoClans
     */
    public function getPlugin(): NarutoClans
    {
        return $this->plugin;
    }


}