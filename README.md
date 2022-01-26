# vIDS - virtual Information Display System
![][version-image]
[![Release date][release-date-image]][release-url]
[![npm license][license-image]][license-url]

Project Overview:
The goal of vIDS is to create a collaboration tool similar in function to the Information Display Systems found in real-world air traffic control facilities. vIDS is tailored to the VATSIM environment (and particularly, VATUSA). Each IDS displays information to controllers in a slightly different format based on position and facility, and vIDS is no different. vIDS is capable of displaying information in 3 different formats: one tailored to a major (Class B Primary) local control, a large TRACON centered on a major, and a multi-IDS view suitable for other TRACONs and ARTCCs. Controllers use vIDS to share information regarding active runways, approach and departure types, posiiton combinations, etc. Additionally, vIDS pulls information from a multitude of data sources to consolidate information necessary for control in a single location.

Server Requirements:
vIDS requires PHP 5.6 or newer. Database support is optional, but if used requires MySQL (or compatible MariaDB) 10 or newer. All other dependencies (Javascript libraries, etc) are accessed via CDN.

Installation:
Clone the repository and make a pull request to your server. 

You will need a VATSIM Connect SSO token to facilitate user authorization and authentication. This process can take 2 weeks or more - go to https://auth.vatsim.net for more information and to submit a request.

Configuration:
1. Submit a request for a VATSIM Connect SSO token via the instructions above if your organization does not already have one. When you've been approved, enter the information in the vars/sso_variables.php file. You'll need your client ID, client secret, redirect URI, and SSO endpoint.

2. Decide if you'd like to use a database to store server-side data or if you'd like to use .dat files. We recommend using a database, as it will make feature upgrades easier for you (simple as a pull request). Reasons you may want to use the .dat files include: no compatible database server, you don't like dolphins, or your ex's name is Maria. Anyway... if you decide to use a database, you'll need to add the applicable connection variables to the vars/db_variables.php file. You'll need the server address, username, password, and database name. Note that if you decide to migrate to a different data management strategy after setting up vIDS, you will lose all user-entered data - there is no way to migrate the data.

3. Open vars/config.php. You guessed it - this is where you tailor (almost) everything in vIDS to your facility. Most of the variables are self-explanatory and commented, so I'll just highlight the ones that warrant additional discussion.
- DEBUG: turns noisy mode on and allows you to see background data for troubleshooting. Don't use this in production - it will cause certain parts of the IDS to malfunction.
- USE_DB: set this to true if you want to use a database instead of the .dat files for server-side storage.
- Controller positions: There are 3 variables in each array: the position name (Ex. "Local Control"), controller IDs within the posiiton (Ex. "LC-1"), and controller IDs that can consolidate the position when working top-down (Ex. "N"). The first variable is a string and the second two variables are arrays. Do not duplicate position IDs between the first array and the second (vIDS automatically includes all items in the first array as valid position combinations). 
- Airfields: This section defines airfields that are displayed in the TRACON view - typically used for satellites in a large TRACON under class B. You can add as many airfields as you like, but the display works best if you have multiples of 3.
- Where is says "DO NOT EDIT BELOW THIS LINE" pro tip - do not edit below that line.

4. You can change the logo in the upper left corner of vIDS grid displays to anything that you'd like. Simply name your image "logo.png" and overwrite the file in the img folder with the same name.

5. The content in the pop-up modals that display airspace diagrams, SOPs, checklists, etc can be edited in the vars/user_modals.php file. Again, do not edit below the "do not edit below this line" line.

6. You can add/remove the rotating background images. They are located in the img/bg folder. Any image file in this folder can be randomly chosen by vIDS as a background for the landing page and menu page.

7. A note about server-side cache: many hosting companies cache files in order to provide faster service. If you are using the .dat files for local storage, this often results in a delay of up to 5 minutes in displaying newly updated information - an obvious problem. We've instituted a number of cache-busting techniques into the code associated with this project in an effort to prevent critical files from being cached. We recommend that you create an .htaccess file in the root of the ids directory and add the following text:
------ Copy below this line to .htaccess ------
RewriteEngine on
RewriteRule ^(.*)\.[\d]{10}\.(css|js)$ $1.$2 [L]

# Disable Caching
<IfModule mod_headers.c>
    Header set Cache-Control "no-cache, no-store, must-revalidate"
    Header set Pragma "no-cache"
    Header set Expires 0
</IfModule>
------ Copy above this line to .htaccess ------

Traffic Management Tools / TMU Tools:
A traffic management plug-in is currently under development for vIDS. At this time, it is not part of the vIDS repository. When configuring vIDS, you should set the TRAFFIC_MANAGEMENT constant to false. Contact the development team for additional information.

Questions/Contact:
The best way to get in touch with the development team is via our Discord. Feel free to ask support and setup questions in the General channel.
https://discord.gg/FXdT8jtd

Thanks for your interest in vIDS!

<!-- Links: -->
[version-image]: https://img.shields.io/github/package-json/v/kjporter/vIDS

[release-date-image]: https://img.shields.io/github/release-date/kjporter/vIDS
[release-url]: https://github.com/kjporter/vIDS/blob/main/releases

[license-image]: https://img.shields.io/github/license/kjporter/vIDS
[license-url]: https://github.com/kjporter/vIDS/blob/main/COPYING

[changelog-url]: https://github.com/kjporter/vIDS/blob/main/CHANGELOG.md

[github-packages-registry]: https://github.com/features/packages