<?php

namespace pixelwhiz\parachuteplus\items;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;

class AutoParachute {

    /**
     * @return Item
     */

    public static function getItem(): Item {
        $item = VanillaBlocks::REDSTONE_TORCH()->asItem();
        $item->setCustomName("AutoParachute");
        return $item;
    }

    /**
     * @return string
     */

    public static function getName(): string {
        return "AutoParachute";
    }

    /**
     * @return int
     */

    public static function getTypeId(): int {
        return VanillaBlocks::REDSTONE_TORCH()->asItem()->getTypeId();
    }
}
