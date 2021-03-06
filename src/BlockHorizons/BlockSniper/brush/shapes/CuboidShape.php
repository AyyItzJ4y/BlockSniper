<?php

declare(strict_types = 1);

namespace BlockHorizons\BlockSniper\brush\shapes;

use BlockHorizons\BlockSniper\brush\BaseShape;
use BlockHorizons\BlockSniper\brush\BrushManager;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;

class CuboidShape extends BaseShape {
	
	public function __construct(Player $player, Level $level, int $width = null, Position $center = null, bool $hollow = false, bool $cloneShape = false) {
		parent::__construct($player, $level, $center, $hollow);
		$this->width = $width;
		$this->height = BrushManager::get($player)->getHeight();
		if($cloneShape) {
			$this->center->y += $this->height;
		}
	}

	/**
	 * @param bool $partially
	 * @param int  $blocksPerTick
	 *
	 * @return array
	 */
	public function getBlocksInside(bool $partially = false, int $blocksPerTick = 100): array {
		$targetX = $this->center->x;
		$targetY = $this->center->y;
		$targetZ = $this->center->z;
		
		$minX = $targetX - $this->width;
		$minY = $targetY - $this->height;
		$minZ = $targetZ - $this->width;
		$maxX = $targetX + $this->width;
		$maxY = $targetY + $this->height;
		$maxZ = $targetZ + $this->width;
		
		$blocksInside = [];
		$i = 0;
		$skipBlocks = 1;
		
		for($x = $minX; $x <= $maxX; $x++) {
			for($y = $minY; $y <= $maxY; $y++) {
				for($z = $minZ; $z <= $maxZ; $z++) {
					if($this->hollow === true) {
						if($x !== $maxX && $x !== $minX && $y !== $maxY && $y !== $minY && $z !== $maxZ && $z !== $minZ) {
							continue;
						}
					}
					if($partially) {
						for($skip = $skipBlocks; $skip <= $this->getProcessedBlocks(); $skip++) {
							$skipBlocks++;
							continue 2;
						}
						if($i > $blocksPerTick) {
							$this->partialBlocks = array_merge($this->partialBlocks, $blocksInside);
							break 3;
						}
						$i++;
					}
					$blocksInside[] = $this->getLevel()->getBlock(new Vector3($x, $y, $z));
				}
			}
		}
		$this->partialBlockCount += $i;
		return $blocksInside;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->hollow ? "Hollow Cuboid" : "Cuboid";
	}

	/**
	 * @return int
	 */
	public function getApproximateProcessedBlocks(): int {
		if($this->hollow) {
			$blockCount = (pow($this->width * 2, 2) * 2) + (($this->width * 2) * ($this->height * 2) * 4);
		} else {
			$blockCount = ($this->width * 2) * ($this->width * 2) * ($this->height * 2);
		}

		return ceil($blockCount);
	}
}
