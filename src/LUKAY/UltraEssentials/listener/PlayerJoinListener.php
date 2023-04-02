<?php

namespace LUKAY\UltraEssentials\listener;

use LUKAY\UltraEssentials\Loader;
use LUKAY\UltraEssentials\VanishManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoinListener implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        if (VanishManager::getInstance()->isVanished($event->getPlayer())) {
            return;
        }
        $event->setJoinMessage(Loader::getInstance()->translate('message-join', Loader::getInstance()->getPrefix(), $event->getPlayer()));
    }
}
