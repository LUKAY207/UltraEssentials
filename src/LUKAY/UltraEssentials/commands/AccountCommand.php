<?php

namespace LUKAY\UltraEssentials\commands;

use LUKAY\UltraEssentials\FormManager;
use LUKAY\UltraEssentials\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class AccountCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.account.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.account.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (empty($args[0])) {
            $sender->sendMessage($loader->translate('command-account-usage-message', $loader->getPrefix()));
            return;
        }
        if (!$loader->getServer()->getPlayerExact($args[0]) instanceof Player) {
            $sender->sendMessage($loader->translate('command-player-not-found', $loader->getPrefix()));
            return;
        }
        $sender->sendForm(FormManager::getInstance()->getAccountForm($sender));
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}
