<?php

namespace pixelwhiz\parachute\commands;

use pixelwhiz\parachute\items\Parachute;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class ParachutesCommands extends Command {

    public function __construct()
    {
        parent::__construct("parachute", "Give parachutes to players", "/parachute give <player name>", ["pc"]);
        $this->setPermission("parachutes.cmd");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("parachutes.cmd")) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return false;
        }

        if (count($args) <= 1) {
            $sender->sendMessage(TextFormat::GRAY . "Usage: ". TextFormat::RED . $this->getUsage());
            return false;
        }

        $playerName = $args[1];
        $player = Server::getInstance()->getPlayerExact($playerName);

        if (!$player instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Player not found!");
            return false;
        }

        $player->getInventory()->addItem(new Parachute());
        $sender->sendMessage(TextFormat::GREEN . "Gave Parachute(s) to {$player->getName()}");
        return true;
    }
}
