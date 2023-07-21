<?php

namespace FFA\modes;

use FFA\Main;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\Position;

class combo {
    private $main;
    public $world;

    public function __construct(Main $main) {
        $this->main = $main;

        $this->main->getServer()->getWorldManager()->loadWorld("Combo");
        $this->world = $this->main->getServer()->getWorldManager()->getWorldByName("Combo");

        $this->main->getLogger()->info("Combo mode is loaded.");
    }

    public function teleport(Player $player) {
        $spawnPoint = new Position(229, 65, 261, $this->world);
        $player->teleport($spawnPoint);
        $player->getInventory()->clearAll();
        $this->give($player);
    }

    public function getPlayerCount() {
        return count($this->world->getPlayers());
    }

    public function give(Player $player ) {
        $diamondSword = VanillaItems::DIAMOND_SWORD();
        $diamondSword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));
        $diamondSword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 2));
        $player->getInventory()->setItem(0, $diamondSword);

        // Add 16 ender pearls to slot 1
        $enderPearl = VanillaItems::ENDER_PEARL();
        $enderPearl->setCount(16);
        $player->getInventory()->setItem(1, $enderPearl);

        $helmet = VanillaItems::DIAMOND_HELMET();
        $helmet->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));

        // Create the diamond chestplate and add Unbreaking enchantment
        $chestplate = VanillaItems::DIAMOND_CHESTPLATE();
        $chestplate->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));

        // Create the diamond leggings and add Unbreaking enchantment
        $leggings = VanillaItems::DIAMOND_LEGGINGS();
        $leggings->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));

        // Create the diamond boots and add Unbreaking enchantment
        $boots = VanillaItems::DIAMOND_BOOTS();
        $boots->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3));

        // Equip the diamond armor to the player
        $player->getArmorInventory()->setHelmet($helmet);
        $player->getArmorInventory()->setChestplate($chestplate);
        $player->getArmorInventory()->setLeggings($leggings);
        $player->getArmorInventory()->setBoots($boots);
    }
}
