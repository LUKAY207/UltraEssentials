<?php

namespace LUKAY\UltraEssentials;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;

class FormManager {
    use SingletonTrait;

    public function getAccountForm(Player $player): SimpleForm {
        $loader = Loader::getInstance();
        $form = new SimpleForm(function (Player $player, int $data = null) {
        });
        $form->setTitle($loader->translate('form-account-title', null, $player));
        $form->setContent($loader->translate('form-account-content', null, null, ['{player}', '{accountCount}', '{accounts}', '{creationDate}' ,'{line}'], [$player->getName(), count(AccountManager::getInstance()->getAccountList($player)), implode(', ', AccountManager::getInstance()->getAccountList($player)), AccountManager::getInstance()->getCreationDate($player), TextFormat::EOL]));
        $form->addButton($loader->translate('form-account-closebutton'));
        return $form;
    }
}
