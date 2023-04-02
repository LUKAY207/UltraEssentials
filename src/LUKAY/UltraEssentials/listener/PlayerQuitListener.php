<?php

namespace LUKAY\UltraEssentials\listener;

use LUKAY\UltraEssentials\Loader;
use LUKAY\UltraEssentials\VanishManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerQuitListener implements Listener {

    public function onQuit(PlayerQuitEvent $event): void {
        if (VanishManager::getInstance()->isVanished($event->getPlayer())) {
            return;
        }
        $event->setQuitMessage(Loader::getInstance()->translate('message-quit', Loader::getInstance()->getPrefix(), $event->getPlayer()));
    }
}
