<?php

namespace LUKAY\UltraEssentials;


use DateTime;
use DateTimeZone;
use Exception;
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
        $accounts = Loader::getInstance()->getPlayerData()->getNested(filter_var($player->getNetworkSession()->getIp(), FILTER_SANITIZE_NUMBER_INT));
        return array_keys($accounts);
    }

    public function getCreationDate(Player $player): string {
        return Loader::getInstance()->getPlayerData()->getNested(filter_var($player->getNetworkSession()->getIp(), FILTER_SANITIZE_NUMBER_INT) . '.' . $player->getName() . '.creationDate');
    }
}