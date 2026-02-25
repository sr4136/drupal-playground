<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello World' Block.
 *
 * @Block(
 *   id = "hello_world_block",
 *   admin_label = @Translation("Hello World Block"),
 *   category = @Translation("Custom"),
 * )
 */
class HelloBlock extends BlockBase {

	/**
	 * {@inheritdoc}
	 */
	public function build() {
		$controller = new \Drupal\hello_world\Controller\HelloController();
		return $controller->content('block'); // Indicate that it's called from the block
	}
}
