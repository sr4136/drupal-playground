# Environment & Setup:

1. Install Docker
    - https://www.drupal.org/docs/getting-started/installing-drupal/install-drupal-using-ddev-for-local-development
    - BUT FIRST, specifically install Ubuntu
      - https://ddev.com/get-started/ `wsl --install Ubuntu --name DDEV`
2. Install Composer
    - `sudo apt install composer`
3. Created a directory and start DDEV within the DDEV terminal (app)
    - `mkdir my-drupal-site && cd my-drupal-site`
    - `ddev config --project-type=drupal11 --docroot=web`
    - `ddev start`
4. Install Drupal
    - `ddev composer create-project "drupal/recommended-project:^11"`
    - `ddev composer require drush/drush`
5. Run Drupal:
    - `ddev drush site:install --account-name=admin --account-pass=admin -y`
    - `ddev launch $(ddev drush uli)`

6. Open in VSCode:
    - `code .`
    - (make sure the terminal in VSCode is set to WSL!!!)
    - Also install VSCode extensions `DDEV Manager`

7. Install StarterKit Theme:
    - https://www.drupal.org/docs/core-modules-and-themes/core-themes/starterkit-theme
    - BUT FIRST, install PHP
      - https://www.allurcode.com/how-to-install-the-latest-php-version-on-windows-subsystem-for-linux-wsl/
    - From root of drual site, run
      - `php web/core/scripts/drupal generate-theme sr_theme`
 
8. Activate theme, transfer custom files, activate modules, set placeholder content
    - Admin > Config > Dev Settings > Twig dev mode, etc.
    - Admin > Appearance > Install sr_theme and set as default
    - Files: copy contents of GH repo: `modules/hello_world` and `themes/sr_theme`
    - Admin > Extend > Custom: activate Hello World
	
9. Now, a new route is available at `https://my-drupal-site.ddev.site/hello`
    - Admin > Structure > Block Layout: add the Hello World Block to the footer.
    - Now you can see that Hello World is on the page twice: once via the page/route and once via the block.
      - And it is context-aware, showing "controller/block context" respectively.
	
10. Install `devel` and `kint` for debugging/dumping.
    - https://www.drupal.org/docs/extending-drupal/contributed-modules/contributed-module-documentation/devel/introduction
    - Install php extensions
      - `sudo apt install php8.3-xml`
      - `sudo apt install php8.3-gd`
      - `sudo apt install php8.3-intl`
      - `sudo apt install php8.3-mbstring`
    - `ddev stop` then `ddev launch`


# Debugging:

- clear the cache:
  - `ddev drush cr`

- if "port 80 error"
  - run `sudo service apache2 stop`
  - run `sudo lsof -i :80` to verify it closed


# My Changes & Customizations:

- Steve's Hello World module
  - `/web/modules/hello_world`
  - Custom Hello controller & block. 
    - the block pulls the content from the controller
    - both are context aware (am I being called from block or controller?)
	
- Custom theme via starterkit
  - https://www.drupal.org/docs/core-modules-and-themes/core-themes/starterkit-theme
  - Breaks the footer template out of page.html.twig into footer.html.twig
    - utilized function in stever.theme
  - Custom template for Hello block
    - and function in stever.theme to pass data to the block template
		
- Added `devel` and `kint` for debugging
