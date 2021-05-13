<?php

namespace ItzLightyHD\KnockbackFFA\command\subcommands;

use ItzLightyHD\KnockbackFFA\Loader;
use ItzLightyHD\KnockbackFFA\utils\GameSettings;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;

class JoinCommand extends BaseSubCommand {

    private $plugin;

    public function __construct(Loader $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("join", "Join the minigame");
    }

    protected function prepare(): void
    {

    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!$sender instanceof Player) {
            $sender->sendMessage("§cDu kannst dies nicht tun!");
            return;
        }
        $world = GameSettings::getInstance()->world;
        if(Server::getInstance()->getLevelByName($world) instanceof Level) {
            if($sender instanceof Player) {
                $sender->teleport(Server::getInstance()->getLevelByName($world)->getSpawnLocation());
            }
        } else {
            $sender->sendMessage("§cEin fehler ist aufgetreten");
        }
    }

}
