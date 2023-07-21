<?php

namespace FFA\modes;

use FFA\Main;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\Position;

class Fist {
    private $main;
    public $world;

    public function __construct(Main $main) {
        $this->main = $main;

        $this->main->getServer()->getWorldManager()->loadWorld("Fist");
        $this->world = $this->main->getServer()->getWorldManager()->getWorldByName("Fist");

        $this->main->getLogger()->info("Fist mode is loaded.");
    }

    public function teleport(Player $player) {
        $spawnPoint = new Position(229, 65, 261, $this->world);
        $player->teleport($spawnPoint);
        $this->give($player);
        $player->getInventory()->clearAll();
    }

    public function getPlayerCount() {
        return count($this->world->getPlayers());
    }

    public function give(Player $player ) {
        $item = VanillaItems::STEAK();
        $item->setCount(64);
        $player->getInventory()->addItem($item);
    }
}
