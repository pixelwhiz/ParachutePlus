<?php


namespace pixelwhiz\parachuteplus\items;


use pocketmine\item\Egg;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Parachute extends Egg {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(ItemTypeIds::EGG), "Parachute");
        $this->setCustomName("Parachute");
    }

    /**
     * @return Item
     */

    public static function getItem(): Item {
        $item = VanillaItems::EGG();
        $item->setCustomName("Parachute");
        return $item;
    }

    /**
     * @param Player $player
     * @return void
     */

    public static function addItem(Player $player): void {
        $player->getInventory()->addItem(self::getItem());
    }

    /**
     * @return string
     */

    public static function getItemName(): string {
        return "Parachute";
    }

    /**
     * @return int
     */

    public static function getItemId(): int {
        return ItemTypeIds::EGG;
    }

}