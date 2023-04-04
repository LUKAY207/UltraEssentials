<?php

namespace LUKAY\UltraEssentials\commands;

use LUKAY\UltraEssentials\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class HealCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.heal.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.heal.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (isset($args[0]) && $sender->hasPermission('ultraessentials.heal.others.command')) {
            $target = $loader->getServer()->getPlayerExact($args[0]);
            if (!$target instanceof Player) {
                $sender->sendMessage($loader->translate('command-player-not-found', $loader->getPrefix()));
                return;
            }
            $target->setHealth($target->getMaxHealth());
            $target->sendMessage($loader->translate('message-heal-got-healed', $loader->getPrefix(), $sender));
            $sender->sendMessage($loader->translate('message-heal-healed-other', $loader->getPrefix(), $sender));
        } else {
            $sender->setHealth($sender->getMaxHealth());
            $sender->sendMessage($loader->translate('message-heal-healed', $loader->getPrefix()));
        }
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}
