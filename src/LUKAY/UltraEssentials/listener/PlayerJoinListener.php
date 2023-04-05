<?php

namespace LUKAY\UltraEssentials\listener;

use Exception;
use LUKAY\UltraEssentials\AccountManager;
use LUKAY\UltraEssentials\Loader;
use LUKAY\UltraEssentials\VanishManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoinListener implements Listener {

    /**
     * @throws Exception
     */
    public function onJoin(PlayerJoinEvent $event): void {
        AccountManager::getInstance()->addAccountToList($event->getPlayer());
        if (VanishManager::getInstance()->isVanished($event->getPlayer())) {
            $event->setJoinMessage('');
            return;
        }
        $event->setJoinMessage(Loader::getInstance()->translate('message-join', Loader::getInstance()->getPrefix(), $event->getPlayer()));
    }
}
