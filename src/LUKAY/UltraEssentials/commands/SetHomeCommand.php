<?php

namespace LUKAY\UltraEssentials\commands;

use JsonException;
use LUKAY\UltraEssentials\HomeManager;
use LUKAY\UltraEssentials\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class SetHomeCommand extends Command implements PluginOwned  {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        $this->setPermission('ultraessentials.sethome.command');
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $loader = Loader::getInstance();
        $homeManager = HomeManager::getInstance();

        if (!$sender instanceof Player) {
            $sender->sendMessage($loader->translate('command-executor-not-player', $loader->getPrefix()));
            return;
        }
        if (!$sender->hasPermission('ultraessentials.sethome.command')) {
            $sender->sendMessage($loader->translate('command-executor-no-permission', $loader->getPrefix()));
            return;
        }
        if (empty($args[0])) {
            $sender->sendMessage($loader->translate('command-sethome-usage-message', $loader->getPrefix()));
            return;
        }
        if ($homeManager->existsHome($sender, $args[0])) {
            $sender->sendMessage($loader->translate('command-home-name-used', $loader->getPrefix()));
            return;
        }
        if ($homeManager->getMax($sender) == Loader::getInstance()->getPlayerData()->getNested($sender->getName() . '.homes.count')) {
            $sender->sendMessage($loader->translate('command-sethome-limit-achieved', $loader->getPrefix()));
            return;
        }
        $homeManager->setHome($sender, $args[0], $sender->getOffsetPosition(new Vector3($sender->getPosition()->getX(), $sender->getPosition()->getY(), $sender->getPosition()->getZ())), $sender->getWorld());
        $sender->sendMessage($loader->translate('message-sethome-created', null, null, ['{prefix}', '{home}'], [$loader->getPrefix(), $args[0]]));
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}