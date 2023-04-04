<?php

namespace LUKAY\UltraEssentials;

use LUKAY\UltraEssentials\commands\DeleteHomeCommand;
use LUKAY\UltraEssentials\commands\FeedCommand;
use LUKAY\UltraEssentials\commands\HealCommand;
use LUKAY\UltraEssentials\commands\HomeCommand;
use LUKAY\UltraEssentials\commands\HomesCommand;
use LUKAY\UltraEssentials\commands\SetHomeCommand;
use LUKAY\UltraEssentials\commands\TPAcceptCommand;
use LUKAY\UltraEssentials\commands\TPACommand;
use LUKAY\UltraEssentials\commands\TPADeclineCommand;
use LUKAY\UltraEssentials\commands\TPAHereCommand;
use LUKAY\UltraEssentials\commands\TPAllCommand;
use LUKAY\UltraEssentials\commands\UnVanishCommand;
use LUKAY\UltraEssentials\commands\VanishCommand;
use LUKAY\UltraEssentials\listener\PlayerJoinListener;
use LUKAY\UltraEssentials\listener\PlayerQuitListener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
class Loader extends PluginBase {
    use SingletonTrait;

    protected function onLoad(): void {
        $this->saveResource('config.yml');
        $this->saveResource('playerData.json');
        Loader::setInstance($this);
    }

    protected function onEnable(): void {
        $config = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents(new PlayerJoinListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerQuitListener(), $this);
        $this->getServer()->getCommandMap()->registerAll('UltraEssentials',
            [
                new DeleteHomeCommand('deletehome', $config->get('command-deletehome-description'), $config->get('command-deletehome-usage-message'), $config->get('command-deletehome-aliases')),
                new HomeCommand('home', $config->get('command-home-description'), $config->get('command-home-usage-message'), $config->get('command-home-aliases')),
                new HomesCommand('homes', $config->get('command-homes-description'), $config->get('command-homes-usage-message'), $config->get('command-homes-aliases')),
                new SetHomeCommand('sethome', $config->get('command-sethome-description'), $config->get('command-sethome-usage-message'), $config->get('command-sethome-aliases')),
                new FeedCommand('feed', $config->get('command-feed-description'), $config->get('command-feed-usage-message'), $config->get('command-feed-aliases')),
                new HealCommand('heal', $config->get('command-heal-description'), $config->get('command-heal-usage-message'), $config->get('command-heal-aliases')),
                new TPACommand('tpa', $config->get('command-tpa-description'), $config->get('command-tpa-usage-message'), $config->get('command-tpa-aliases')),
                new TPAHereCommand('tpahere', $config->get('command-tpahere-description'), $config->get('command-tpahere-usage-message'), $config->get('command-tpahere-aliases')),
                new TPAcceptCommand('tpaccept', $config->get('command-tpaccept-description'), $config->get('command-tpaccept-usage-message'), $config->get('command-tpaccept-aliases')),
                new TPADeclineCommand('tpadecline', $config->get('command-tpadecline-description'), $config->get('command-tpadecline-usage-message'), $config->get('command-tpadecline-aliases')),
                new TPAllCommand('tpall', $config->get('command-tpall-description'), $config->get('command-tpall-usage-message'), $config->get('command-tpall-aliases')),
                new UnVanishCommand('unvanish', $config->get('command-unvanish-description'), $config->get('command-unvanish-usage-message'), $config->get('command-unvanish-aliases')),
                new VanishCommand('vanish', $config->get('command-vanish-description'), $config->get('command-vanish-usage-message'), $config->get('command-vanish-aliases'))
            ]);
    }

    public function getConfig(): Config {
        return new Config($this->getDataFolder() . 'config.yml', Config::YAML);
    }

    public function getPlayerData(): Config {
        return new Config($this->getDataFolder() . 'playerData.json', Config::JSON);
    }

    public function getPrefix(): string {
        return $this->getConfig()->get('prefix');
    }

    public function translate(string $key, string $prefix = null, Player $player = null, array|string $customToReplace = null, array|string $customKeys = null): string {
        if ($prefix === null && $player === null && $customKeys === null) {
            return $this->getConfig()->get($key);
        } elseif ($prefix !== null && $player === null && $customKeys === null) {
            return str_replace('{prefix}', $prefix, $this->getConfig()->get($key));
        } elseif ($prefix === null && $player !== null && $customKeys === null) {
            return str_replace('{player}', $player->getName(), $this->getConfig()->get($key));
        } elseif ($prefix === null && $player === null && $customKeys !== null) {
            return str_replace($customToReplace, $customKeys, $this->getConfig()->get($key));
        }
        return str_replace(['{prefix}', '{player}'], [$prefix, $player->getName()], $this->getConfig()->get($key));
    }
}