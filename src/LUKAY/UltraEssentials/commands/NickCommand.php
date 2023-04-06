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

class NickCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.nick.command');
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
        if (!$sender->hasPermission('ultraessentials.nick.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (empty($args[0])) {
            $sender->sendMessage($loader->translate('command-nick-usage-message', $loader->getPrefix()));
            return;
        }
        if (!$accountManager->isAllowedNick($args[0])) {
            $sender->sendMessage($loader->translate('command-nick-not-allowed', $loader->getPrefix()));
            return;
        }
        $accountManager->setNickname($sender, implode(' ', $args));
        $sender->sendMessage($loader->translate('message-nick-nicked', null, null, ['{prefix}', '{nick}'], [$loader->getPrefix(), implode(' ', $args)]));
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}
