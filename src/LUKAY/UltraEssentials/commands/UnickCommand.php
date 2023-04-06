<?php

namespace LUKAY\UltraEssentials\commands;

use JsonException;
use LUKAY\UltraEssentials\AccountManager;
use LUKAY\UltraEssentials\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class UnickCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.unick.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();
        $accountManager = AccountManager::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.unick.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (isset($args[0]) && $sender->hasPermission('ultraessentials.unick.others.command')) {
            if (!$loader->getServer()->getPlayerExact($args[0]) instanceof Player) {
                $sender->sendMessage($loader->translate('command-player-not-found', $loader->getPrefix()));
                return;
            }
            if (!$accountManager->isNicked($loader->getServer()->getPlayerExact($args[0]))) {
                $sender->sendMessage($loader->translate('command-unick-player-not-nicked', $loader->getPrefix(), $loader->getServer()->getPlayerExact($args[0])));
                return;
            }
            $accountManager->unsetNickname($loader->getServer()->getPlayerExact($args[0]));
            $sender->sendMessage($loader->translate('message-unick-unicked-player', $loader->getPrefix(), $loader->getServer()->getPlayerExact($args[0])));
            $loader->getServer()->getPlayerExact($args[0])->sendMessage($loader->translate('message-unick-got-unicked', $loader->getPrefix(), $sender));
        } else {
            $accountManager->unsetNickname($sender);
            $sender->sendMessage($loader->translate('message-unick-unicked', $loader->getPrefix()));
        }
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}
