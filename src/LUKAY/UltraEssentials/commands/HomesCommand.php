<?php

namespace LUKAY\UltraEssentials\commands;

use LUKAY\UltraEssentials\HomeManager;
use LUKAY\UltraEssentials\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class HomesCommand extends Command implements PluginOwned  {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.homes.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();
        $homeManager = HomeManager::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if ($loader->getPlayerData()->getNested($sender->getName() . '.homeCount') == 0) {
            $sender->sendMessage($loader->translate('command-homes-no-homes', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.homes.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        $sender->sendMessage($loader->translate('message-homes', $loader->getPrefix()) . implode(', ', array_keys($homeManager->getHomes($sender))));
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}