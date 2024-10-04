<?php

namespace pixelwhiz\parachuteplus\commands;

use pixelwhiz\parachuteplus\items\Parachute;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;

class ParachutePlusCommands extends Command implements PluginOwned {

    use PluginOwnedTrait;
    public Plugin $plugin;

    public function __construct(Plugin $plugin)
    {
        parent::__construct("parachuteplus", "Give parachute to player", "/parachute give <player name>", ["parachute", "pc"]);
        $this->setPermission("parachuteplus.cmd");
        $this->plugin = $plugin;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return false;
        }

        if (count($args) < 2) {
            $sender->sendMessage(TextFormat::GRAY . "Usage: " . TextFormat::RED . $this->getUsage());
            return false;
        }

        $playerName = $args[1];
        $player = Server::getInstance()->getPlayerExact($playerName);

        if (!$player instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Player not found!");
            return false;
        }

        $player->getInventory()->addItem(new Parachute());
        $sender->sendMessage(TextFormat::GREEN . "Gave Parachute(s) to " . $player->getName());
        return true;
    }
}
