<?php

/**
 * Copyright 2020-2022 LiTEK - Josewowgame2888
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
namespace litek\clans\form\types;

use Closure;
use litek\clans\form\elements\Button;
use pocketmine\form\FormValidationException;
use pocketmine\Player;
use function array_map;
use function array_merge;
use function is_int;
use function is_string;

class MenuForm extends Form
{
	/** @var string */
	private $content;
	/** @var Button[] */
	private $buttons = [];

	public function __construct(string $title, string $content = "", array $buttons = [], ?Closure $onSubmit = null, ?Closure $onClose = null)
	{
		parent::__construct($title);
		$this->content = $content;
		$this->append(...$buttons);
		if ($onSubmit !== null) {
			$this->onSubmit($onSubmit);
		}
		if ($onClose !== null) {
			$this->onClose($onClose);
		}
	}

	/**
	 * @return string
	 */
	protected function getType(): string
	{
		return self::TYPE_MENU;
	}

	/**
	 * @return callable
	 */
	protected function getOnSubmitCallableSignature(): callable
	{
		return function (Player $player, Button $selected): void {
		};
	}

	/**
	 * @return array
	 */
	protected function serializeFormData(): array
	{
		return [
			"buttons" => $this->buttons,
			"content" => $this->content
		];
	}

	/**
	 * @param Button|string ...$buttons
	 * @return self
	 */
	public function append(...$buttons): self
	{
		if (isset($buttons[0])) {
			if (is_string($buttons[0])) {
				$buttons = array_map(function (string $text): Button {
					return new Button($text);
				}, $buttons);
			} else {
				(function (Button ...$_) {
				})(...$buttons);
			}
		}
		$this->buttons = array_merge($this->buttons, $buttons);
		return $this;
	}

	final public function handleResponse(Player $player, $data): void
	{
		if ($data === null) {
			if ($this->onClose !== null) {
				($this->onClose)($player);
			}
		} elseif (is_int($data)) {
			if (!isset($this->buttons[$data])) {
				throw new FormValidationException("Button with index $data does not exist");
			}
			if ($this->onSubmit !== null) {
				$button = $this->buttons[$data];
				$button->setValue($data);
				($this->onSubmit)($player, $button);
			}
		} else {
			throw new FormValidationException("Expected int or null, got " . gettype($data));
		}
	}
}