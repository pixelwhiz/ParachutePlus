<?php

namespace pixelwhiz\parachute\entity;


use pocketmine\math\Vector3;
use pixelwhiz\parachute\Parachutes;
use pocketmine\block\Air;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class Chicken extends Living {


    public function getName(): string
    {
        return "Chicken";
    }

    protected function getInitialGravity(): float
    {
        return 0.005;
    }
    
    public function entityBaseTick(int $tickDiff = 1): bool
    {
        $player = $this->getTargetEntity();
        if (!$player instanceof Player) return false;
        $this->location->yaw = $player->getLocation()->getYaw();
        $this->location->pitch = $player->getLocation()->getPitch();
        $pos = new Vector3($player->getLocation()->getX(), $player->getLocation()->getY() - 1, $player->getLocation()->getZ());
        $block = $player->getWorld()->getBlock($pos);
        if (!$block instanceof Air) {
            Parachutes::despawnParachute($player);
            return false;
        }
        $direction = $this->getDirectionVector();
        $this->move($direction->getX() / 0.9, 0, $direction->getZ() / 0.9);
        return true;
    }

    protected function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(0.4, 0.4);
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::CHICKEN;
    }
}