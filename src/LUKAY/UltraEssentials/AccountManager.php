<?php

namespace LUKAY\UltraEssentials;


use DateTime;
use DateTimeZone;
use Exception;
use JsonException;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class AccountManager {
    use SingletonTrait;

    /**
     * @throws Exception
     */
    public function addAccountToList(Player $player): void {
        $playerData = Loader::getInstance()->getPlayerData();
        $ip = $player->getNetworkSession()->getIp();
        $now = new DateTime('now',  new DateTimeZone(Loader::getInstance()->getConfig()->get('timezone')));
        if ($playerData->getNested(filter_var($ip, FILTER_SANITIZE_NUMBER_INT) . '.' . $player->getName()) === null) {
            $playerData->setNested(filter_var($ip, FILTER_SANITIZE_NUMBER_INT) . '.' . $player->getName() . '.creationDate', $now->format('d.m.y H:i'));
            $playerData->save();
            $playerData->reload();
        }
    }

    public function getAccountList(Player $player): array {
        $accounts = Loader::getInstance()->getPlayerData()->getNested($this->getIp($player));
        return array_keys($accounts);
    }

    public function getIp(Player $player): int {
        return filter_var($player->getNetworkSession()->getIp(), FILTER_SANITIZE_NUMBER_INT);
    }

    public function getCreationDate(Player $player): string {
        return Loader::getInstance()->getPlayerData()->getNested($this->getIp($player) . '.' . $player->getName() . '.creationDate');
    }

    /**
     * @throws JsonException
     */
    public function setNickname(Player $player, string $nickname): void {
        $playerData = Loader::getInstance()->getPlayerData();
        $player->setDisplayName($nickname . '§f');
        $player->setNameTag($nickname);
        $playerData->setNested($this->getIp($player) . '.' . $player->getName() . '.nickname', $nickname);
        $playerData->save();
        $playerData->reload();
    }

    /**
     * @throws JsonException
     */
    public function unsetNickname(Player $player): void {
        $playerData = Loader::getInstance()->getPlayerData();
        $player->setDisplayName($player->getName());
        $player->setNameTag($player->getName());
        $playerData->removeNested($this->getIp($player) . '.' . $player->getName() . '.nickname');
        $playerData->save();
        $playerData->reload();
    }

    public function isNicked(Player $player): bool {
        $playerData = Loader::getInstance()->getPlayerData();
        if ($playerData->getNested($this->getIp($player) . '.' . $player->getName() . '.nickname') === null) {
            return false;
        }
        return true;
    }

    public function isAllowedNick(string $nickname): bool {
        $playerData = Loader::getInstance()->getPlayerData();
        foreach (array_keys($playerData->getAll()) as $ip) {
            if ($playerData->getNested($ip . '.' . $nickname) !== null) {
                return false;
            }
        }
        return true;
    }
}