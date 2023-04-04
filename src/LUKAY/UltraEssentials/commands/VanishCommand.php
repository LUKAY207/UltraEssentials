<?php

namespace LUKAY\UltraEssentials\commands;

use LUKAY\UltraEssentials\Loader;
use LUKAY\UltraEssentials\VanishManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class VanishCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.vanish.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();
        $vanishManager = VanishManager::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.vanish.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (isset($args[0]) && $sender->hasPermission('ultraessentials.vanish.others.command')) {
            if (!$loader->getServer()->getPlayerExact($args[0]) instanceof Player) {
                $sender->sendMessage($loader->translate('command-player-not-found', $loader->getPrefix()));
                return;
            }
            if ($vanishManager->isVanished($loader->getServer()->getPlayerExact($args[0]))) {
                $sender->sendMessage($loader->translate('command-vanish-vanished-target', $loader->getPrefix(), $loader->getServer()->getPlayerExact($args[0])));
                return;
            }
            $sender->sendMessage($loader->translate('message-vanished-target-executor', $loader->getPrefix(), $loader->getServer()->getPlayerExact($args[0])));
            $loader->getServer()->getPlayerExact($args[0])->sendMessage($loader->translate('message-vanished-target', $loader->getPrefix(), $sender));
            $vanishManager->vanish($loader->getServer()->getPlayerExact($args[0]));
        } else {
            if ($vanishManager->isVanished($sender)) {
                $sender->sendMessage($loader->translate('command-vanish-vanished'));
                return;
            }
            $sender->sendMessage($loader->translate('message-vanished', $loader->getPrefix()));
            $vanishManager->vanish($sender);
        }
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}