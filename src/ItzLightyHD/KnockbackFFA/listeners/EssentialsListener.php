<?php

namespace ItzLightyHD\KnockbackFFA\listeners;

use ItzLightyHD\KnockbackFFA\Loader;
use ItzLightyHD\KnockbackFFA\utils\GameSettings;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;

class EssentialsListener implements Listener {

    /** @var Loader $plugin */
    private $plugin;
    /** @var self $instance */
    protected static $instance;

    public function __construct(Loader $plugin)
    {
        $this->plugin = $plugin;
        self::$instance = $this;
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function onBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        if($player->getLevel()->getFolderName() == GameSettings::getInstance()->world) {
            $event->setCancelled();
        }
    }

    public function onHunger(PlayerExhaustEvent $event) {
        if($event->getPlayer()->getLevel()->getFolderName() === GameSettings::getInstance()->world) {
            $event->setCancelled(true);
        }
    }

    public function onDrop(PlayerDropItemEvent $event): void {
        $player = $event->getPlayer();
        if($player->getLevel()->getFolderName() === GameSettings::getInstance()->world) {
            $event->setCancelled();
        }
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        if($player->getLevel()->getFolderName() === GameSettings::getInstance()->world) {
            if($event->getItem()->getCustomName() == "§r§eLeap§r") {
                if(!isset($this->cooldown[$player->getName()])) $this->cooldown[$player->getName()] = 0;
                if($this->cooldown[$player->getName()] <= time()) {
                    $directionvector = $player->getDirectionVector()->multiply(4 / 2);
                    $dx = $directionvector->getX();
                    $dy = $directionvector->getY();
                    $dz = $directionvector->getZ();
                    $player->setMotion(new Vector3($dx, 1, $dz));
                    $this->cooldown[$player->getName()] = time() + 10;
                } else $player->sendMessage(GameSettings::getInstance()->getConfig()->get("prefix") . "§r§cDu musst §e" . (10 - ((time() + 10) - $this->cooldown[$player->getName()])) . "§c Sekunden warten biss du das Leap wieder nutzen kannst.");
            }
        }
    }

}
