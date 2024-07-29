<?php

declare(strict_types=1);

namespace pixelwhiz\parachute;

use PhpParser\Node\Stmt\Foreach_;
use pixelwhiz\parachute\commands\ParachutesCommands;
use pixelwhiz\parachute\entity\Chicken;
use pixelwhiz\parachute\items\AutoParachute;
use pixelwhiz\parachute\items\Parachute;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\item\Stick;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

use pixelwhiz\parachute\listeners\EventListener;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use pocketmine\world\World;

class Main extends PluginBase {

    public static Main $instance;

    protected function onLoad(): void
    {
        self::getLogger()->notice("Optimizing chicken entity ...");
    }

    protected function onEnable(): void
    {
        Server::getInstance()->getPluginManager()->registerEvents(new EventListener(), $this);
        EntityFactory::getInstance()->register(Chicken::class, function (World $world, CompoundTag $nbt): Chicken {
            return new Chicken(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ["Chicken"]);
        self::$instance = $this;
        Parachutes::clearEntity();
        Server::getInstance()->getCommandMap()->register("parachute", new ParachutesCommands());
    }

    public static function getInstance(): Main {
        return self::$instance;
    }
    
    protected function onDisable(): void
    {
        parent::onDisable();
    }

}
