#!/bin/bash

<VirtualHost *:81>

	ServerAdmin webmaster@localhost
	ServerName ScraperBot
	DocumentRoot /home/pi/ScraperBot

	<Directory /home/pi/ScraperBot>
	    Options Indexes FollowSymLinks Includes ExecCGI
	    AllowOverride All
	    Require all granted
	    Allow from all
	</Directory>
</VirtualHost>



cat file >> copy_file
