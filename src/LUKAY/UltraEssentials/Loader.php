<?php

namespace LUKAY\UltraEssentials;

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
        Loader::setInstance($this);
    }

    protected function onEnable(): void {
        $config = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents(new PlayerJoinListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerQuitListener(), $this);
        $this->getServer()->getCommandMap()->registerAll('UltraEssentials',
            [
                new TPACommand('tpa', $config->get('command-tpa-description'), $config->get('command-tpa-usage-message'), $config->get('command-tpa-aliases')),
                new TPAHereCommand('tpahere', $config->get('command-tpahere-description'), $config->get('command-tpahere-usage-message'), $config->get('command-tpahere-aliases')),
                new TPAcceptCommand('tpaccept', $config->get('command-tpaccept-description'), $config->get('command-tpaccept-usage-message'), $config->get('command-tpaccept-aliases')),
                new TPADeclineCommand('tpadecline', $config->get('command-tpadecline-description'), $config->get('command-tpadecline-usage-message'), $config->get('command-tpadecline-aliases')),
                new TPAllCommand('tpall', $config->get('command-tpall-description'), $config->get('command-tpall-usage-message'), $config->get('command-tpall-aliases')),
                new VanishCommand('vanish', $config->get('command-vanish-description'), $config->get('command-vanish-usage-message'), $config->get('command-vanish-aliases')),
                new UnVanishCommand('unvanish', $config->get('command-unvanish-description'), $config->get('command-unvanish-usage-message'), $config->get('command-unvanish-aliases'))
            ]);
    }

    public function getConfig(): Config {
        return new Config($this->getDataFolder() . 'config.yml', Config::YAML);
    }

    public function getPrefix(): string {
        return $this->getConfig()->get('prefix');
    }

    public function translate(string $key, string $prefix = null, Player $player = null): string {
        if ($prefix === null && $player === null) {
            return $this->getConfig()->get($key);
        } elseif ($prefix !== null && $player === null) {
            return str_replace('{prefix}', $prefix, $this->getConfig()->get($key));
        } elseif ($prefix === null && $player !== null) {
            return str_replace('{player}', $player->getName(), $this->getConfig()->get($key));
        }
        return str_replace(['{prefix}', '{player}'], [$prefix, $player->getName()], $this->getConfig()->get($key));
    }
}