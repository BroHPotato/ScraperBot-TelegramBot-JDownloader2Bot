#!/bin/bash

<VirtualHost *:81>

	ServerAdmin webmaster@localhost
	ServerName ScraperBot
	DocumentRoot /path/to/ScraperBot

	<Directory /path/to/ScraperBot>
	    Options Indexes FollowSymLinks Includes ExecCGI
	    AllowOverride All
	    Require all granted
	    Allow from all
	</Directory>
</VirtualHost>



cat file >> copy_file
