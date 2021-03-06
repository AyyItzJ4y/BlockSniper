<?php

declare(strict_types = 1);

namespace BlockHorizons\BlockSniper\brush\types;

use BlockHorizons\BlockSniper\brush\BaseType;
use BlockHorizons\BlockSniper\brush\BrushManager;
use pocketmine\block\Block;
use pocketmine\block\Flowable;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;

class ToplayerType extends BaseType {
	
	/*
	 * Replaces the top layer of the terrain, thickness depending on brush height, within the brush radius.
	 */
	public function __construct(Player $player, Level $level, array $blocks) {
		parent::__construct($player, $level, $blocks);
	}

	/**
	 * @return array
	 */
	public function fillShape(): array {
		$undoBlocks = [];
		foreach($this->blocks as $block) {
			if($block->getId() !== Item::AIR && !$block instanceof Flowable) {
				if($block->getSide(Block::SIDE_UP)->getId() === Item::AIR || $block->getSide(Block::SIDE_UP) instanceof Flowable) {
					$randomBlock = BrushManager::get($this->player)->getBlocks()[array_rand(BrushManager::get($this->player)->getBlocks())];
					for($y = $block->y; $y >= $block->y - BrushManager::get($this->player)->getHeight(); $y--) {
						$undoBlocks[] = $this->level->getBlock(new Vector3($block->x, $y, $block->z));
						$this->level->setBlock(new Vector3($block->x, $y, $block->z), $randomBlock, false, false);
					}
				}
			}
		}
		return $undoBlocks;
	}
	
	public function getName(): string {
		return "Top Layer";
	}
}
