<?php

namespace pixelwhiz\parachute\listeners;

use pixelwhiz\parachute\entity\Chicken;
use pixelwhiz\parachute\items\AutoParachute;
use pixelwhiz\parachute\items\Parachute;
use pixelwhiz\parachute\Parachutes;
use pocketmine\block\Air;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\player\Player;

class EventListener implements Listener {

    private static array $cooldowns = [];

    public function onUse(PlayerItemUseEvent $event) {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        if ($item->getTypeId() === Parachute::getItemId() && $item->getCustomName() === Parachute::getItemName()) {
            if (isset(self::$cooldowns[$player->getName()]) && time() < self::$cooldowns[$player->getName()]) {
                $remainingTime = self::$cooldowns[$player->getName()] - time();
                $player->sendMessage("§7Cooldown! please wait §c$remainingTime §7seconds again.");
                $event->cancel();
                return false;
            }

            Parachutes::spawnParachute($player);
            self::$cooldowns[$player->getName()] = time() + 5;
        }

        if ($item->getCustomName() === AutoParachute::getName() && $item->getTypeId() === AutoParachute::getTypeId()) {
            Parachutes::despawnParachute($player);
        }

        $event->cancel();
    }

    public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if ($entity instanceof Chicken) {
            $event->cancel();
        }

        if ($entity instanceof Player) {
            if ($event->getCause() === $event::CAUSE_FALL) {
                if ($entity->canClimbWalls()) {
                    $event->cancel();
                }

                if (Parachutes::isParachuteMode($entity)) {
                    $event->cancel();
                }
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        $hand = $event->getItem();
        if ($hand->getTypeId() === AutoParachute::getTypeId() && $hand->getCustomName() === AutoParachute::getName()) {
            $event->cancel();
        }
    }

    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        if (Parachutes::isParachuteMode($player)) Parachutes::despawnParachute($player);
    }

    public function onReceive(DataPacketReceiveEvent $event) {
        $packet = $event->getPacket();
        $player = $event->getOrigin()->getPlayer();
        if ($packet instanceof InteractPacket) {
            if ($packet->action === InteractPacket::ACTION_LEAVE_VEHICLE && Parachutes::isParachuteMode($player)) {
                Parachutes::despawnParachute($player);
            }
        }
    }

}