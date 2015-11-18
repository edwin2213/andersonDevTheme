# RM Theme
ver 1.0.0

A mobile-first starter kit for building websites at Rosemont Media. The goal of this theme isn't to set a whole bunch of defaults for you. Rather, the idea is to give you all the tools you need to help you efficiently move from design to production with minimal hiccups.

Before starting, make sure you're familiar with the Rosemont Style Guide: [http://rosemontdev.com/codedump/style-guide/](http://rosemontdev.com/codedump/style-guide/).

## How to use

The theme is built with the idea that you'll build in the order that the Sass file is set up:

	1. Variables
	2. Typography
	3. Common UI elements (i.e., buttons)
	4. Overall layout
	5. Common theme elements (i.e., header, nav, footer, etc)
	6. Specific templates (i.e., home page, blog page, custom landing pages)
	
Generic media queries are provided for reference. The actual breakpoints of these should follow the design and content of the individual site.

There's a variety of helper classes included in the Sass file for things like images, alignment/floats, text, etc. These should be used where they make sense within the template rather than repeating their definitions within individual element styles.

## Getting Started

Download (or pull into a bare repo) the theme to your new site. Terminal into the folder for the theme and run: `sudo npm install`. This will grab all the theme dependencies and install them in a node_modules folder.

Change the information at the top of the sass/styles.scss folder to match the specific client.

## Functions

The standard RM helper functions are all included in /includes/rm-functions.php. Site-wide custom functions should be written at the bottom of the primary functions.php file. Any template specific functions (ie, those just used on the Home page or a custom landing page) should be included in a separate file in the includes folder, with the "inc.php" extension, ie, "includes/home.inc.php". These are automatically included by the rm-functions file.

## Images

All images should go in the "images" folder, with a sub-folder ("images/svg") for SVGs. This pattern needs to be maintained for some of the functions in "includes/rm-functions.php" to work.

## Gulp

There's a default Gulp file setup to be used with the theme. Running `gulp watch` from the command line (within the theme folder) will:

	* Watch any .scss file in the sass folder for changes and compile style.css
	* Watch any top level .js file in the js folder for changes and run jshint (for javascript errors)
	* Watch for changes in any php file
	
Any of the above changes will trigger live reload in any browser which has the live reload plugin installed. You can download the plugin for your preferred browser from the [Live Reload Extensions page](http://livereload.com/extensions/). Follow instructions there for setup.

In addition, `gulp images` will optimize any jpg/png/gif found within the "images" folder.


## Deployment

Once uploaded to the live server, be sure to delete the .git repository and .gitignore file, as well as package.json, gulpfile.js, and and entire node_modules folder. Alternatively, exclude them from your zip/tar file before uploading.



### Questions/Thoughts/Concerns/Ideas?

Email ruben@rosemontmedia, miked@rosemontmedia, or matt@rosemontmedia. Or, you know, just yell at one of us.