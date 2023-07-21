<?php

namespace FFA;

use FFA\modes\combo;
use FFA\modes\fist;
use FFA\modes\nodebuff;
use pocketmine\plugin\PluginBase;
use jojoe77777\FormAPI\Form;
use jojoe77777\FormAPI\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {
    public Item $item;
    public static Main $main;
    public Fist $fist;
    public combo $combo;
    public nodebuff $nodebuff;
    public array $openedForms = [];

    public function onEnable() : void {
        self::$main = $this;
        $this->fist = new fist($this);
        $this->combo = new combo($this);
        $this->nodebuff = new nodebuff($this);
        $this->getLogger()->info("FFA Activated.");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }


    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {


        if ($sender instanceof Player) {
            if ($command->getName() === "lobby") {
                $world = $sender->getWorld()->getFolderName();
                if ($this->nodebuff->world->getFolderName() === $world || $this->combo->world->getFolderName() === $world || $this->fist->world->getFolderName() === $world) {
                    $def = $this->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();
                    $sender->teleport($def);
                    $sender->getInventory()->clearAll();
                    $sender->getArmorInventory()->clearAll();
                    $sender->getInventory()->setItem(0, $this->item);
                } else {
                    $sender->sendMessage("You are already in the lobby.");
                }
            }
        }
            return true;
        }


    public function openInterface(Player $player) {
        if (isset($this->openedForms[$player->getName()])) {
            return;
        }




        $this->openedForms[] = $player->getName();
        $form = new SimpleForm(function(Player $player, int $data = null) {
            if ($data === null) {
                return null;
            }

            unset($this->openedForms[$player->getName()]);

            switch($data) {
                case 0:
                    # COMBO
                   
                break;

                case 1:
                    # FIST
                    $this->fist->teleport($player);
                break;

                case 2:
                    #NODEBUFF
                    $this->nodebuff->teleport($player);
                break;
            }
        });

        $form->setTitle(TextFormat::BOLD . TextFormat::BLUE . "➤ " . TextFormat::YELLOW . "P" . TextFormat::AQUA . "C" . TextFormat::RED . "P" . TextFormat::WHITE . ": " . TextFormat::BOLD . TextFormat::BLUE . "Free for All");
        $form->addButton(TextFormat::BOLD . TextFormat::BLUE . "➤ " . TextFormat::YELLOW . "Combo" . TextFormat::RESET . ": " . TextFormat::DARK_GREEN  . $this->fist->getPlayerCount() . "/10", 1, "https://static.wikia.nocookie.net/minecraft_gamepedia/images/6/6a/Diamond_Sword_JE2_BE2.png/revision/latest?cb=20200217235945");
        $form->addButton(TextFormat::BOLD . TextFormat::BLUE . "➤ " . TextFormat::YELLOW . "Fist" . TextFormat::RESET . ": " . TextFormat::DARK_GREEN . "0/10", 1, "https://static.wikia.nocookie.net/minecraft_gamepedia/images/f/fb/Steak_JE3_BE2.png/revision/latest?cb=20190504055100");
        $form->addButton(TextFormat::BOLD . TextFormat::BLUE . "➤ " . TextFormat::YELLOW . "Nodebuff" . TextFormat::RESET . ": " . TextFormat::DARK_GREEN. "0/10", 1, "https://static.wikia.nocookie.net/minecraftuniverse/images/3/38/Splash_Potion_of_Healing_II.png/revision/latest?cb=20131118203518");
        $form->sendToPlayer($player);
    } 

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $item = VanillaItems::DIAMOND();
        $item->setCustomName("FFA");
        $player->getInventory()->setItem(0, $item);
        $this->item = $item;
    }

    public function onDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();

        $world = $player->getWorld()->getFolderName();
        if ($this->nodebuff->world->getFolderName() === $world || $this->combo->world->getFolderName() === $world || $this->fist->world->getFolderName() === $world) {
            $player->teleport($this->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
        }
    }
    public function onInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();

        if ($item->getName() === $this->item->getName()) {
            $this->openInterface($player);
        }
    }


}