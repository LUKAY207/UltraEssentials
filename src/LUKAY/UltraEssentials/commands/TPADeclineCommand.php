<?php

namespace LUKAY\UltraEssentials\commands;

use LUKAY\UltraEssentials\Loader;
use LUKAY\UltraEssentials\TeleportManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class TPADeclineCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.tpadecline.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();
        $teleportManager = TeleportManager::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.tpadecline.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (!$teleportManager->exists($sender)) {
            $sender->sendMessage($loader->translate('command-tpa-exists-not', $loader->getPrefix()));
            return;
        }
        $requester = $teleportManager->getRequester($sender);
        $sender->sendMessage($loader->translate('message-tpadecline', $loader->getPrefix(), $requester));
        $requester->sendMessage($loader->translate('message-tpadecline-target', $loader->getPrefix(), $sender));
        $teleportManager->decline($sender);
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}