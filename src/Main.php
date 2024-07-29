<?php

declare(strict_types=1);

namespace pixelwhiz\parachute;

use pixelwhiz\parachute\commands\ParachutesCommands;
use pixelwhiz\parachute\entity\Chicken;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

use pixelwhiz\parachute\listeners\EventListener;
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
