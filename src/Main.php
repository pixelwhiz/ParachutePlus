<?php

declare(strict_types=1);

namespace pixelwhiz\parachuteplus;

use pixelwhiz\parachuteplus\commands\ParachutePlusCommands;
use pixelwhiz\parachuteplus\entity\Chicken;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

use pixelwhiz\parachuteplus\listeners\EventListener;
use pocketmine\world\World;

class Main extends PluginBase {

    public static Main $instance;

    /**
     * @description: ParachutePlus main instance
     *
     * @return Main
     */
    public static function getInstance(): self {
        return self::$instance;
    }

    protected function onLoad(): void
    {
        self::$instance = $this;
        Parachutes::clearEntity();
    }

    protected function onEnable(): void
    {
        Server::getInstance()->getPluginManager()->registerEvents(new EventListener($this), $this);
        EntityFactory::getInstance()->register(Chicken::class, function (World $world, CompoundTag $nbt): Chicken {
            return new Chicken(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ["Chicken"]);

        self::$instance = $this;
        Server::getInstance()->getCommandMap()->register("parachuteplus", new ParachutePlusCommands($this));
    }

    protected function onDisable(): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if (Parachutes::isParachuteMode($player)) {
                Parachutes::despawnParachute($player);
            }
        }
    }
}
