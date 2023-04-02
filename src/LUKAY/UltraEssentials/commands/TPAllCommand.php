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

class TPAllCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.tpall.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();
        $vanishManager = VanishManager::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.tpall.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        foreach ($loader->getServer()->getOnlinePlayers() as $player) {
            if ($vanishManager->isVanished($player)) {
                return;
            }
            $player->teleport($sender->getLocation());
        }
        $sender->sendMessage($loader->translate('message-tpall', $loader->getPrefix()));
        $loader->getServer()->broadcastMessage($loader->translate('message-tpall-target', $loader->getPrefix(), $sender));
    }


    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}