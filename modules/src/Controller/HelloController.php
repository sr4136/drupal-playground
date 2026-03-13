<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Session\AccountInterface;

class HelloController {
    /* Gets the current user's name. */
    public function getCurrentUserName() {
        $current_user = \Drupal::currentUser();
        $user = \Drupal\user\Entity\User::load($current_user->id());

        return $user instanceof AccountInterface ? $user->getDisplayName() : 'Anonymous';
    }

    /* Output the content. */
    public function content($context = 'controller') {
        $name = $this->getCurrentUserName();

        $output_structure = 'From all of us at [@context context]: HELLO WORLD, and specifically <strong><i>@name</i></strong>!';
        $output_vars = ['@name' => $name, '@context' => $context];
        $output_translated = t($output_structure, $output_vars);

        // Update the render array to match block output
        return [
            '#type' => 'container',
            '#attributes' => [
                'class' => [
                    'contextual-region',
                    'block-stever-helloworld',
                    'stever-helloworld',
                    'context-'.$context
                ],
            ],
            'content' => [
                '#type' => 'markup',
                '#markup' => $output_translated,
            ],
        ];
    }
}
