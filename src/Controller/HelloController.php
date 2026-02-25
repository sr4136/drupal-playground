<?php

/**
 * @file
 * Contains \Drupal\hello_world\Controller\HelloController.
 */

namespace Drupal\hello_world\Controller;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;



class HelloController {
	/* Gets the current user's name. */
	public function getCurrentUserName() {

		// Get the current user service.
		$current_user = \Drupal::currentUser();

		// Load the user entity to access more information.
		$user = \Drupal\user\Entity\User::load($current_user->id());

		// Get the user's name (full name).
		if ($user instanceof AccountInterface) {
			$name = $user->getDisplayName(); // Gets the display name.
		} else {
			$name = 'Anonymous';
		}

		return $name;
	}

	/* Output the content. */
	public function content($context = 'controller') {
		$name = $this->getCurrentUserName(); // Get the user's name.

		$output_structure = 'From all of us at [@context]: Hello world, and specifically <strong><i>@name</i></strong>!';
		$output_vars = ['@name' => $name, '@context' => $context];
		$output_translated = t($output_structure, $output_vars);

		return array(
			'#type' => 'markup',
			'#markup' => $output_translated,
		);
	}
}
