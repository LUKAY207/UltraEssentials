<?php

namespace LUKAY\UltraEssentials;

use JsonException;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;
use pocketmine\world\World;

class HomeManager {
    use SingletonTrait;

    public function getMax(Player $player): int {
        if (!Loader::getInstance()->getPlayerData()->exists($player->getName() . '.homes.max')) {
            Loader::getInstance()->getPlayerData()->setNested($player->getName() . '.homes.max', Loader::getInstance()->getConfig()->get('default-max-homes'));
            return Loader::getInstance()->getConfig()->get('default-max-homes');
        }
        return Loader::getInstance()->getPlayerData()->getNested($player->getName() . '.homes.max');
    }

    /**
     * @throws JsonException
     */
    public function setMax(Player $player, int $max): void {
        $playerData = Loader::getInstance()->getPlayerData();
        $playerData->setNested($player->getName() . '.homes.max', $max);
        $playerData->save();
        $playerData->reload();
    }

    /**
     * @throws JsonException
     */
    public function setHome(Player $player, string $homeName, Vector3 $position, World $world): void {
        $playerData = Loader::getInstance()->getPlayerData();
        $playerData->setNested($player->getName() . '.homeCount', $playerData->getNested($player->getName() . '.homeCount') + 1);
        $playerData->setNested($player->getName() . '.homes.' . $homeName . '.position.x', $position->getFloorX());
        $playerData->setNested($player->getName() . '.homes.' . $homeName . '.position.y', $position->getFloorY());
        $playerData->setNested($player->getName() . '.homes.' . $homeName . '.position.z', $position->getFloorZ());
        $playerData->setNested($player->getName() . '.homes.' . $homeName . '.position.world', $world->getFolderName());
        $playerData->save();
        $playerData->reload();
    }

    /**
     * @throws JsonException
     */
    public function deleteHome(Player $player, string $homeName): void {
        $playerData = Loader::getInstance()->getPlayerData();
        $playerData->setNested($player->getName() . '.homeCount', $playerData->getNested($player->getName() . '.homeCount') - 1);
        $playerData->removeNested($player->getName() . '.homes.' . $homeName);
        $playerData->save();
        $playerData->reload();
    }

    public function existsHome(Player $player, string $homeName): bool {
        $playerData = Loader::getInstance()->getPlayerData();
        if ($playerData->getNested($player->getName() . '.homes.' . $homeName) === null) {
            return false;
        }
        return true;
    }

    public function getHomes(Player $player): array {
        return Loader::getInstance()->getPlayerData()->getNested($player->getName() . '.homes');
    }

    public function getPosition(Player $player, string $homeName): Position {
        $playerData = Loader::getInstance()->getPlayerData();
        return new Position($playerData->getNested($player->getName() . '.homes.' . $homeName . '.position.x'), $playerData->getNested($player->getName() . '.homes.' . $homeName . '.position.y'), $playerData->getNested($player->getName() . '.homes.' . $homeName . '.position.z'), Loader::getInstance()->getServer()->getWorldManager()->getWorldByName($playerData->getNested($player->getName() . '.homes.' . $homeName . '.position.world')));
    }
}