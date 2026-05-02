# My Changes & Customizations:

Demo of the below at: [https://steverudolfi.com/misc/drupal/hello](https://steverudolfi.com/misc/drupal/hello)

1. Hello World module
	  - [Custom router](https://github.com/sr4136/drupal-playground/blob/main/modules/hello_world.routing.yml) to "add a page" at `/hello`
	  - [Custom controller](https://github.com/sr4136/drupal-playground/blob/main/modules/src/Controller/HelloController.php)
    	- Pulls the [logged in user's name](https://github.com/sr4136/drupal-playground/blob/main/modules/src/Controller/HelloController.php#L17-L33) (defaults to "Anonymous" if not) to say hello
  	- [Custom block](https://github.com/sr4136/drupal-playground/blob/main/modules/src/Plugin/Block/HelloBlock.php) to be placed in a Block Layout, say the Footer.
   		- the block [pulls the content from the controller](https://github.com/sr4136/drupal-playground/blob/main/modules/src/Plugin/Block/HelloBlock.php#L22-L23)
  	- Both controller and block are context aware (am I being called from [block](https://github.com/sr4136/drupal-playground/blob/main/modules/src/Plugin/Block/HelloBlock.php#L23) or [controller](https://github.com/sr4136/drupal-playground/blob/main/modules/src/Controller/HelloController.php#L36)?)
	
2. Custom theme via [starterkit](https://www.drupal.org/docs/core-modules-and-themes/core-themes/starterkit-theme)
  	- Template override to break the footer template out of [page.html.twig](https://github.com/sr4136/drupal-playground/blob/main/themes/sr_theme/templates/layout/page.html.twig#L81-L83) into [footer.html.twig](https://github.com/sr4136/drupal-playground/blob/main/themes/sr_theme/templates/layout/footer.html.twig)
  	- [Custom template](https://github.com/sr4136/drupal-playground/blob/main/themes/sr_theme/templates/block/block--hello-world-block.html.twig) for Hello block
    	- and function in `sr_theme.theme` to [pass title data to the block template](https://github.com/sr4136/drupal-playground/blob/main/themes/sr_theme/sr_theme.theme#L24-L32)
     	- including outputting classes to designate the context (`.context-block` and `.context-controller`) to style accordingly
	- [Added](https://github.com/sr4136/drupal-playground/blob/main/themes/sr_theme/sr_theme.libraries.yml#L5) some [custom styles for the hello block](https://github.com/sr4136/drupal-playground/blob/main/themes/sr_theme/css/components/sr-hello.css)
 	- <img width="980" height="484" alt="firefox_qs3RrlaWWv" src="https://github.com/user-attachments/assets/9f816d25-1415-4f83-bd9a-1c41ea62e399" />


	
# Environment & Setup:

(On a Windows 11 computer with WSL.)

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


-------------------------------
# Further customizations:

## 1. Enable DevelGenerate & use it to create sample content.

- Enable module
    - `ddev drush en devel_generate -y`
- Generate 20 article nodes with lorem ipsum body text
    - `ddev drush devel-generate:content 20 --bundles=article`
- Generate 5 basic pages
    - `ddev drush devel-generate:content 5 --bundles=page`
- Generate menus (main + footer) with 5 links each
    - `ddev drush devel-generate:menu --menus=main,footer --links=5`
- Generate 10 users
    - `ddev drush devel-generate:users 10`
- Generate taxonomy terms (replace 'tags' with your vocabulary machine name)
    - `ddev drush devel-generate:terms 20 --bundles=tags`
- Clear caches
    - `ddev drush cr`


## 2. Convert theme to use SASS
- update [package.json](https://github.com/sr4136/drupal-playground/blob/main/themes/sr_theme/package.json), add `build` and `watch` scripts with sourcemap params.
- create base(vairables/mixins/etc) folder: [sr_theme/scss/base](https://github.com/sr4136/drupal-playground/tree/main/themes/sr_theme/scss/base)
- move *existing* CSS to the sass/comonents directory, replacing repetitive values/params with variables, etc [sr_theme/scss/components](https://github.com/sr4136/drupal-playground/tree/main/themes/sr_theme/scss/base)
- create *new* sass file [sr_theme/scss/components/global](https://github.com/sr4136/drupal-playground/blob/main/themes/sr_theme/scss/components/global.scss) for global site styles
  
## 3. Add styles & twig/theme customizations
- [added base reset & rough structure](https://github.com/sr4136/drupal-playground/commit/4125d959f3eb9b14040cb78673c0a1581e68a5bb)
- [variables unification and cleanup](https://github.com/sr4136/drupal-playground/commit/016d03ab1630be2442ac08e214dbda71bdbc6a89)
	- then [apply those unified variables to individual files](https://github.com/sr4136/drupal-playground/commit/d96c731371139709141b7e9ab9ef5004a7f1d6f9)
- [menu styling: horizontal menu, hover dropdown, search box](https://github.com/sr4136/drupal-playground/commit/a48eb09d266ad5177c33e49d5501bfaaf6f17537)
- [meta styling: author info, tags, publish date](https://github.com/sr4136/drupal-playground/commit/76079df052ec49f508370c3fd68d36667c4f4358)
	- edit `node.html.twig` to remove the "on" from the published date, since it's on its own line
- [breadcrumb styling](https://github.com/sr4136/drupal-playground/commit/35740225cb0872aa738aaf94acfe69060c953a97)
	- edit `breadcrumb.html.twig` and `sr_theme.theme` to show the current page in the breadcrumbs
- <img width="1169" height="664" alt="firefox_WockCHVa1X" src="https://github.com/user-attachments/assets/79fb28af-2582-49ec-9b16-170b45d650ff" />
- <img width="1166" height="708" alt="firefox_REwx9FLFSt" src="https://github.com/user-attachments/assets/e2f062d4-e135-4e7b-8937-be94293f15af" />
- <img width="1175" height="856" alt="firefox_5Pjc1ibJom" src="https://github.com/user-attachments/assets/9d102d20-9250-4895-9827-26d805ad5ffd" />



## 4. Add modules: Group & Paragraphs
- [composer.json: remove unnecessary initialization cruft](https://github.com/sr4136/drupal-playground/commit/92157921ef13f7e9b79f37d36219307522a75ac3)
- [Install Group & Paragraphs](https://github.com/sr4136/drupal-playground/commit/651de47a3a4ca4d2c1ae91c730adf381502fd9ae)
	- `ddev composer require drupal/group:^3.0 drupal/paragraphs:^1.17` and enable them `ddev drush en group paragraphs -y`
- *Group Module*: rough idea: group control for Article content type. 
	- added "Editorial" group, added two group types: "Editorial Admin" & "Editorial Contributors"
	- added Michelle as Editorial Admin
	- added Bob & Carl as Editorial Contributors
	- Configure available content-- enabled "Group node (Article)"
	- Edit group type permissions-- for the groups' articles permissions
 		<img width="767" height="626" alt="firefox_5T47P5BUKK" src="https://github.com/user-attachments/assets/0530ac6b-52d9-4452-b997-1e81dfbaf283" />
- *Paragraphs Module*: rough idea: a "Futher Reading" component with an image and link/description to other pages.
	- Created Paragraph types: Further Reading Item & Further Reading List.
		- Further Reading Item contains: Reference to content & description.
		- Further Reading List contains: Paragraph type (Further Reading Item) & Image.
	- Added Content Type for Basic Page: Paragraph type
		- Selected Further Reading List
	- Edited display for Item & List to hide some of the labels
 	-  <img width="986" height="674" alt="firefox_UpbZfZAG5S" src="https://github.com/user-attachments/assets/24aaa710-e1c9-4b80-90aa-bc6896e5b628" />
	- Added twig files to theme for overriding both the [Further Reading Item](https://github.com/sr4136/drupal-playground/blob/abd51635b842a633c7c7125e6c0303c263eb028c/themes/sr_theme/templates/content/paragraph--further-reading.html.twig) & [Further Reading List](https://github.com/sr4136/drupal-playground/blob/abd51635b842a633c7c7125e6c0303c263eb028c/themes/sr_theme/templates/content/paragraph--further-reading-list.html.twig) components.
   	- Added [paragraphs-components.scss](https://github.com/sr4136/drupal-playground/blob/abd51635b842a633c7c7125e6c0303c263eb028c/themes/sr_theme/scss/components/paragraphs-components.scss) for the two components.
 	- Added to new css to [sr_theme.libraries.yml](https://github.com/sr4136/drupal-playground/blob/abd51635b842a633c7c7125e6c0303c263eb028c/themes/sr_theme/sr_theme.libraries.yml#L38)
  - <img width="939" height="924" alt="firefox_qFaKzKlK1q" src="https://github.com/user-attachments/assets/cd6b7399-c075-4dec-9bb8-901d9f873bc9" />


