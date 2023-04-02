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

class TPAcceptCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentialstp.tpaccept.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();
        $teleportManager = TeleportManager::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentialstp.tpaaccept.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (!$teleportManager->exists($sender)) {
            $sender->sendMessage($loader->translate('command-tpa-exists-not', $loader->getPrefix()));
            return;
        }
        $requester = $teleportManager->getRequester($sender);
        if ($teleportManager->getType($sender) == 'tpaStandard') {
            $sender->sendMessage($loader->translate('message-tpaccept-target', $loader->getPrefix(), $requester));
            $requester->sendMessage($loader->translate('message-tpaccept', $loader->getPrefix(), $sender));
        } elseif ($teleportManager->getType($sender) === 'tpaHere') {
            $sender->sendMessage($loader->translate('message-tpahereaccept-target', $loader->getPrefix(), $requester));
            $requester->sendMessage($loader->translate('message-tpahereaccept', $loader->getPrefix(), $sender));
        }
        $teleportManager->accept($sender);
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}