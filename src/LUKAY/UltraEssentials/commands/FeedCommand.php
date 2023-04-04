<?php

namespace LUKAY\UltraEssentials\commands;

use LUKAY\UltraEssentials\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class FeedCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.feed.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.feed.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (isset($args[0]) && $sender->hasPermission('ultraessentials.feed.others.command')) {
            $target = $loader->getServer()->getPlayerExact($args[0]);
            if (!$target instanceof Player) {
                $sender->sendMessage($loader->translate('command-player-not-found', $loader->getPrefix()));
                return;
            }
            $target->getHungerManager()->setFood($target->getHungerManager()->getMaxFood());
            $target->sendMessage($loader->translate('message-feed-got-fed', $loader->getPrefix(), $sender));
            $sender->sendMessage($loader->translate('message-feed-fed-other', $loader->getPrefix(), $sender));
        } else {
            $sender->getHungerManager()->setFood($sender->getHungerManager()->getMaxFood());
            $sender->sendMessage($loader->translate('message-feed-fed', $loader->getPrefix()));
        }
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}