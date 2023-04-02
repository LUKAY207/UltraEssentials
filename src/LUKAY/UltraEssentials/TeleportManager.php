<?php

namespace LUKAY\UltraEssentials;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class TeleportManager {
    use SingletonTrait;

    private array $teleportation = [];

    public function send(Player $teleportRequester, Player $teleportSubject, string $type = 'tpaStandard'): void {
        if ($type === 'tpaStandard') {
            $this->teleportation[$teleportSubject->getName()] = ['requestBy' => $teleportRequester->getName(), 'time' => time(), 'type' => 'tpaStandard'];
        } elseif ($type === 'tpaHere') {
            $this->teleportation[$teleportSubject->getName()] = ['requestBy' => $teleportRequester->getName(), 'time' => time(), 'type' => 'tpaHere'];
        }
    }

    public function exists(Player $teleportSubject): bool {
        if (isset($this->teleportation[$teleportSubject->getName()]) && $this->teleportation[$teleportSubject->getName()]['time'] + Loader::getInstance()->getConfig()->get('tpa-expire-after-time') <= time()) {
            unset($this->teleportation[$teleportSubject->getName()]);
            return false;
        } elseif (!isset($this->teleportation[$teleportSubject->getName()])) {
            return false;
        }
        return true;
    }

    public function accept(Player $teleportSubject): void {
        $teleportRequester = Loader::getInstance()->getServer()->getPlayerExact($this->teleportation[$teleportSubject->getName()]['requestBy']);
        if ($this->teleportation[$teleportSubject->getName()]['type'] === 'tpaStandard') {
            $teleportRequester->teleport($teleportSubject->getLocation());
            unset($this->teleportation[$teleportSubject->getName()]);
        } elseif ($this->teleportation[$teleportSubject->getName()]['type'] === 'tpaHere') {
            $teleportSubject->teleport($teleportRequester->getLocation());
            unset($this->teleportation[$teleportRequester->getName()]);
        }
    }

    public function decline(Player $teleportSubject): void {
        if (isset($this->teleportation[$teleportSubject->getName()])) {
            unset($this->teleportation[$teleportSubject->getName()]);
        }
    }

    public function getType(Player $teleportSubject): string {
        return $this->teleportation[$teleportSubject->getName()]['type'];
    }

    public function getRequester(Player $teleportSubject): Player {
        return Loader::getInstance()->getServer()->getPlayerExact($this->teleportation[$teleportSubject->getName()]['requestBy']);
    }
}

