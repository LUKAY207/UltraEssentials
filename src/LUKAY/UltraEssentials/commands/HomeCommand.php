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

class HomeCommand extends Command implements PluginOwned  {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.home.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();
        $homeManager = HomeManager::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.home.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (empty($args[0])) {
            $sender->sendMessage($loader->translate('command-home-usage-message', $loader->getPrefix()));
            return;
        }
        if (!$homeManager->existsHome($sender, $args[0])) {
            $sender->sendMessage($loader->translate('command-home-not-found', $loader->getPrefix()));
            return;
        }
        $sender->sendMessage($loader->translate('message-home-teleport', null, null, ['{prefix}', '{home}'], [$loader->getPrefix(), $args[0]]));
        $sender->teleport($homeManager->getPosition($sender, $args[0]));
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}
