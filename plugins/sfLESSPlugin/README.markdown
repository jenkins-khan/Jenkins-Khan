# sfLESSPlugin #

*less.js in symfony.*

sfLESSPlugin is a plugin for symfony applications. It's descendant of sfLessPhpPlugin, but with LESS2 compiler in mind.

It can do most of `less.js` can:

* Automatically parse your application `.less` files through LESS and outputs CSS files;
* Automatically include your application `.less` files into page source & enable browser-side LESS parsing.

## LESS & less.js ##

LESS extends CSS with: variables, mixins, operations and nested rules. For more information, see [http://lesscss.org](http://lesscss.org).

less.js is LESS2. It's written on pure JavaScript & can be runned on both sides - in browser or in node.js.

## Installation ##

### Using symfony plugin:install ###

Use this to install sfLESSPlugin:

	$ symfony plugin:install sfLESSPlugin

### Using git clone ###

Use this to install as a plugin in a symfony app:

	$ cd plugins && git clone git://github.com/everzet/sfLESSPlugin.git

### Using git submodules ###

Use this if you prefer to use git submodules for plugins:

	$ git submodule add git://github.com/everzet/sfLESSPlugin.git plugins/sfLESSPlugin
	$ git submodule init
	$ git submodule update

and enable plugin in your ProjectConfigurations class.

## Usage ##

### Prepare ###

After installation, you need to create directory `web/less`. Any LESS file placed in this directory, including subdirectories, will
automatically be parsed through LESS and saved as a corresponding CSS file in `web/css`. Example:

	web/less/clients/screen.less => web/css/clients/screen.css

### Style partials ###

If you prefix a file with an underscore, it is considered to be a partial, and will not be parsed unless included in another file. Example:

	<file: web/less/clients/partials/_form.less>
	@text_dark: #222;
	
	<file: web/less/clients/screen.less>
	@import "partials/_form";
	
	input { color: @text_dark; }

The example above will result in a single CSS file in `web/css/clients/screen.css`.

## Setup ##

sfLESSPlugin can use 2 workflows to manage your *.less files:

1. Compile on browser side by `less.js`;
2. Compile on server side by `lessc`.

### prepare: Update layout ###

For both flows, you need to update your layout files (at least the ones using less stylesheets):

* include the less css helper:

		<?php use_helper('LESS'); ?>

* update the way stylesheets are included by changing `<?php include_stylesheets() ?>` for `<?php include_less_stylesheets() ?>`

### 1st way: Compile on browser side ###

This is default plugin behaviour. In this behaviour, all stylesheets ending with `.less`, added in:

* your `view.yml` configs:

		stylesheets:      [header/main.less]

* a template view file:

		<?php use_stylesheet('header/main.less') ?>

In this case, it will be automatically changed from something like:

	<link href="/css/header/main.less" media="screen" rel="stylesheet" type="text/css" />

to link like:

	<link href="/less/header/main.less" media="screen" rel="stylesheet/less" type="text/css" />

and will add link to `less.js` into javascripts list.

This will cause browser to parse your linked less files on the fly through `less.js`.

### 2nd way: Compile on server side ###

In details, sfLESSPlugin server side compiler does the following:

* Recursively looks for LESS (`.less`) files in `web/less`
* Ignores partials (prefixed with underscore: `_partial.less`) - these can be included with `@import` in your LESS files
* Saves the resulting CSS files to `web/css` using the same directory structure as `web/less`

You have to install 2 packages:

1. `node.js` - server side interp., based on Google V8 JS engine;
2. `less.js` - `LESS2`. You can install this with Node Package Manager (`npm install less`).

After that, enable server behavior & disable browser behavior in `app.yml`:

	sf_less_plugin:
	  compile:              true
	  use_js:               false

In this case, sfLESSPlugin will try to find all your less files inside `web/less/*.less` & compile them into `web/css/*.css`, so you can link your less styles as default css stylesheets:

	stylesheets:            [main.css]

or (best way) with:

	stylesheets:            [main.less]

so `include_less_stylesheets` helper will automatically change `.less` extension to `.css`, but you still will have ability to change compiler type (server side <-> browser side) on the fly with single change in `app.yml`

## Configuration ##

sfLESSPlugin server side compiler rechecks `web/less/*.less` at every routes init. To prevent this, add this in your apps/APP/config/app.yml:

	prod:
	  sf_less_plugin:
	    compile:            false

sfLESSPlugin server side compiler checks the dates of LESS & CSS files, and will by default compile again only if LESS file have been changed since last parsing .

When you use `@import` statements in your LESS files to include partials (styles with `_` prefix), you should also turn on dependencies checking (because, less compiler will not rerun on partials change) in one of you app.yml:

	dev:
	  sf_less_plugin:
	    check_dates:        true
	    check_dependencies: true

**warning:** Checking for the dependencies will affect performances and should not be turned on in production

The safest (but probably slowest) option is to enforce everytime compiling:

	dev:
	  sf_less_plugin:
	    check_dates:        false

Also, sfLESSPlugin server side compiler has Web Debug Panel, from which you can view all styles to compile & can open them for edit in prefered editor. For that you need to configure `sf_file_link_format` in `settings.yml`.

Last but not least, you can enable CSS compression (remove of whitespaces, tabs & newlines) in server side compiler with:

	all:
	  sf_less_plugin:
	    use_compression:    true

In order to workaround [a flaw in the less compiler](http://github.com/cloudhead/less.js/issues#issue/49) you can use the following option:

	all:
	  sf_less_plugin:
	    fix_duplicate:      true

## Tasks ##

sfLESSPlugin server side compiler provides a set of CLI tasks to help compiling your LESS styles.

To parse all LESS files and save the resulting CSS files to the destination path, run:

	$ symfony less:compile

To delete all compiled CSS (only files, that been compiled from LESS files) files before parsing LESS, run:

	$ symfony less:compile --clean

If you want to compress CSS files after compilation, run:

	$ symfony less:compile --compress

Also, by default tasks don't use settings from app.yml such as "path". But you can specify application option to tell compiler from which app to get config:

	$ symfony less:compile --application=frontend

If you want to compile specific file, not all, just use argument & file name without ".less". To compile style.less into style.css, just run:

	$ symfony less:compile style


## Contributors ##

* everzet (lead): [http://github.com/everzet](http://github.com/everzet)
* vicb (contributor): [http://github.com/vicb](http://github.com/vicb)

less.js is maintained by Alexis Sellier [http://github.com/cloudhead](http://github.com/cloudhead)

## History ##

### github latest ###

* updated readme & some refactorings
* dependency check improvements
* [client side] less.js updated to version 1.0.33

### v1.1.0 released on 2010-07-01 ###

* added an helper to include less stylesheets in layouts / templates
* [client side] less javascript updated to version 1.0.30
* [server side] added a visual feedback in the debug bar when some files have been compiled
* [server side] added an optional dependency check 