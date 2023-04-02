<?php

namespace LUKAY\UltraEssentials;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class VanishManager {
    use SingletonTrait;

    private array $vanishedPlayer = [];

    public function isVanished(Player $player): bool {
        return isset($this->vanishedPlayer[$player->getName()]);
    }
}
