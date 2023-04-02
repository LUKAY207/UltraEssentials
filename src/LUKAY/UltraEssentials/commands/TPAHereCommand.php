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

class TPAHereCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.tpahere.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();
        $teleportManager = TeleportManager::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.tpahere.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (empty($args[0])) {
            $sender->sendMessage($loader->translate('command-tpahere-usage-message', $loader->getPrefix()));
            return;
        }
        $teleportSubject = Loader::getInstance()->getServer()->getPlayerExact($args[0]);
        if ($teleportSubject === null) {
            $sender->sendMessage($loader->translate('command-player-not-found', $loader->getPrefix()));
            return;
        }
        if ($args[0] === $sender->getName()) {
            $sender->sendMessage($loader->translate('command-tpa-unnecessary-teleportation', $loader->getPrefix()));
            return;
        }
        $teleportManager->send($sender, $teleportSubject, 'tpaHere');
        $sender->sendMessage($loader->translate('message-sent-tpahere', $loader->getPrefix(), $teleportSubject));
        $teleportSubject->sendMessage($loader->translate('message-sent-tpahere-target', $loader->getPrefix(), $sender));
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}