<?php

declare(strict_types = 1);

namespace BlockHorizons\BlockSniper\brush\shapes;

use BlockHorizons\BlockSniper\brush\BaseShape;
use BlockHorizons\BlockSniper\brush\BrushManager;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Player;

class PyramidShape extends BaseShape {
	
	public function __construct(Player $player, Level $level, int $width = null, Position $center = null, bool $hollow = false) {
		parent::__construct($player, $level, $center, $hollow);
		$this->width = $width;
		$this->height = BrushManager::get($player)->getHeight();
	}
	
	public function getBlocksInside(bool $partially = false, int $blocksPerTick = 100): array {
		// TODO: Implement getBlocksInside() method.
	}

	public function getName(): string {
		return $this->hollow ? "Hollow Pyramid" : "Pyramid";
	}
	
	public function getApproximateProcessedBlocks(): int {
		//TODO: Implement Pyramids
	}
}
