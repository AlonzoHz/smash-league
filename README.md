#Smash League Webpage
The Smash League framework is a PHP/SQL based web application that formats and and analyzes Super Smash Brother's statistics in a meaningful way using the Elo rating system. You can see the original website in action using results from GSMST Smash League's first summer season here: http://alonzoh.me/smash/
##Installation Notes
All of the files are avaiable, but some editing needs to be done before they are completely ready to interface with a server.
1. Put the database connection information in both of the connect.php files: the one in inc and in admin.
2. Download [JpGraph](http://jpgraph.net/download/) and place the files in both inc and admin.
3. Create the proper table structure within your SQL database (more specific outline later)
Note that future versions will not have reduntant dependencies like the program does right now.
##What's already there
The currently available version of the application offers the visible visualization and analytical tools in additon to a number of hidden administrative tools. There are table based data input pages where the admin can create new tournaments, matches, and players. Other tools include refactoring tools to correct Elo and other statistics after a data input error and historical tools to see what statistics looked like at a certain point.
##Goals
The goal of this project is to turn the web app created for my own league into a platform that can be easily used by league managers to externalize data about their player's success. The two main focuses for the near future are ironing out features and making the program more accessible to people without web development experience. 
###Features
Since I originally made this application for my own use, there are a number of tools that are hidden in the code but are not made visible. These will be made available in case the user wants them. In addition, I'll try to make it easy to request new features that can be plugged into the original program easily. There are also features that are displayed on the screen, but that I never really implemented. I'll make sure that there is no useless features.
###Accessibility
The first iteration of the program was basically developed on the server, such that every little update was pushed to the my server and tested. A full release intended for public use needs to be more omnibus, or in other words, easier to maintain for people that are not experienced in webservers and databases. Since the application does require a webhost, it can't be completely simple which is something that I hope to remedy with adequate documentation. Right now, a lot of the data input is manual based on tournament brackets, but I hope to automate it, perhaps based on reading bracket 
##Closing words
Thanks for your interest in the project! If you have suggestions/questions, please contact me at alonzo (dot) hernandez (at) gatech.edu.
