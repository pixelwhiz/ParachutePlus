<?php


namespace pixelwhiz\parachuteplus;

use pixelwhiz\parachuteplus\entity\Chicken;
use pixelwhiz\parachuteplus\items\AutoParachute;
use pixelwhiz\parachuteplus\items\Parachute;
use pixelwhiz\parachuteplus\utils\RandomUtils;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBroadcastUtils;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityLink;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class Parachutes {

    /**
     * @description: Add parachute to player
     *
     * @param Player $player
     * @return bool
     */

    public static function spawnParachute(Player $player) {
        $hand = $player->getInventory()->getItemInHand();
        if ($hand->getTypeId() !== Parachute::getItemId() && $hand->getCustomName() !== Parachute::getItemName()) return false;

        $player->getInventory()->setItemInHand(AutoParachute::getItem());
        $player->setCanClimbWalls(true);
        $nbt = RandomUtils::createBaseNBT($player->getPosition());
        $entity = new Chicken($player->getLocation(), $nbt);
        $player->setTargetEntity($entity);
        $entity->setTargetEntity($player);
        $entity->spawnToAll();

        $link = new SetActorLinkPacket();
        $link->link = new EntityLink($entity->getId(), $player->getId(), EntityLink::TYPE_RIDER, true, true, 1.0);
        $player->getNetworkProperties()->setVector3(EntityMetadataProperties::RIDER_SEAT_POSITION, new Vector3(0, -0.5, 0));
        $player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::CAN_FLY, true);
        $player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::RIDING, true);
        $player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::SADDLED, true);
        $player->getNetworkProperties()->setGenericFlag(EntityMetadataFlags::WASD_CONTROLLED, true);
        NetworkBroadcastUtils::broadcastPackets($player->getWorld()->getPlayers(), [$link]);
        return true;
    }

    /**
     * @description: Despawn or Remove parachute from player
     *
     * @param Player $player
     * @return void
     */

    public static function despawnParachute(Player $player) {
        $hand = $player->getInventory()->getItemInHand();
        if ($hand->getTypeId() === AutoParachute::getTypeId() && $hand->getCustomName() === AutoParachute::getName()) {
            $player->getInventory()->setItemInHand(new Parachute());
        }

        $entity = $player->getTargetEntity();
        if ($entity instanceof Chicken) $entity->close();
        ## give slow and flying effect when the player after landing xd
        $player->setCanClimbWalls(true);
        Main::getInstance()->getScheduler()->scheduleDelayedTask(new CancelClimbWalls($player), 20);
    }

    /**
     * @description: Clear all entities from all worlds
     *
     * @return void
     */

    public static function clearEntity() : void {
        foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            foreach ($world->getEntities() as $entity) {
                if ($entity instanceof Chicken) {
                    $entity->close();
                }
            }
        }
    }

    /**
     * @description: Check player is on parachute or not
     *
     * @param Player $player
     * @return bool
     */

    public static function isParachuteMode(Player $player): bool {
        $entity = $player->getTargetEntity();
        if (!$entity instanceof Chicken) return false;
        return true;
    }

}

class CancelClimbWalls extends Task {

    private Player $player;

    /**
     * @param Player $player
     */

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function onRun(): void
    {
        $this->player->setCanClimbWalls(false);
        $this->getHandler()->cancel();
    }

}