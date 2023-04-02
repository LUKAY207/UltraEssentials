<?php

namespace LUKAY\UltraEssentials;

use pocketmine\network\mcpe\convert\SkinAdapterSingleton;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class VanishManager {
    use SingletonTrait;

    private array $vanishedPlayers = [];

    public function isVanished(Player $target): bool {
        return isset($this->vanishedPlayers[$target->getName()]);
    }

    public function vanish(Player $target): void {
        $this->vanishedPlayers[$target->getName()] = true;
        foreach (Loader::getInstance()->getServer()->getOnlinePlayers() as $player) {
            if (!$player->hasPermission('ultraessentials.see.vanished')) {
                $player->sendMessage(Loader::getInstance()->translate('message-quit', Loader::getInstance()->getPrefix(), $target));
                $player->hidePlayer($target);
                $target->showPlayer($target);
                $playerList = new PlayerListPacket();
                $playerList->type = PlayerListPacket::TYPE_REMOVE;
                $playerList->entries[] = PlayerListEntry::createRemovalEntry($target->getUniqueId());
                $player->getNetworkSession()->sendDataPacket($playerList);
            }
        }
    }

    public function unvanish(Player $target): void {
        unset($this->vanishedPlayers[$target->getName()]);
        foreach (Loader::getInstance()->getServer()->getOnlinePlayers() as $player) {
            if (!$player->hasPermission('ultraessentials.see.vanished')) {
                $player->sendMessage(Loader::getInstance()->translate('message-join', Loader::getInstance()->getPrefix(), $target));
                $player->showPlayer($target);
                $playerList = new PlayerListPacket();
                $playerList->type = PlayerListPacket::TYPE_ADD;
                $playerList->entries[] = PlayerListEntry::createAdditionEntry($target->getUniqueId(), $target->getId(), $target->getDisplayName(), SkinAdapterSingleton::get()->toSkinData($target->getSkin()));
                $player->getNetworkSession()->sendDataPacket($playerList);
            }
        }
    }
}
