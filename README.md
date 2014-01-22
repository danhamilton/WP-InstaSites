WP-InstaSites
===================================
The InstaSite plugin provides direct integration into the InstaManager application.  InstaManager is cloud based vacation rental software which powers vacation rental property managers around the world.

Features
- Responsive themes
- Automatic synchronization of availabliy
- Set of widgets for featured properties, specials, property details, availability calendars, property finders, developments, etc...
- Support for vacation rentals, hotels, resorts and B&B
- Multi-language support
- Booking website
- SEO Friendly
- Channel manager (FlipKey, TripAdvisor, Homeaway, VRBO, etc...)
- Social Media & Lead Management
- Owner's Extranet
- Unlimited Email & Phone Support
- Comprehensive Report Suite

Powered by InstaManager (http://www.instamanager.com)
Requires an API key from InstaManager.  Go to http://www.instamanager.com/contactus to request a key.


============================
Installation Considerations
============================
- Apache mod_rewrite must be enabled.  Use command 'a2enmod rewrite' and then restart apache for this change to take effect.
- .htaccess file must contain the following:
	# BEGIN WordPress
	<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteBase /
	RewriteRule ^index\.php$ - [L]
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule . /index.php [L]
	</IfModule>
	# END WordPress
- We recommend using "Day and Name" for permalink settings at first.  Other modes may require different rewrite rules in .htaccess (Wordpress Requirement)