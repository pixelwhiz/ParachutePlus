<?php

namespace pixelwhiz\parachute\items;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;

class AutoParachute {

    public static function getItem(): Item {
        $item = VanillaBlocks::REDSTONE_TORCH()->asItem();
        $item->setCustomName("AutoParachute");
        return $item;
    }

    public static function getName(): string {
        return "AutoParachute";
    }

    public static function getTypeId(): int {
        return VanillaBlocks::REDSTONE_TORCH()->asItem()->getTypeId();
    }
}
