# Welcome to DroughtWise!
<br></br>
If you are a starter who has no experience in using WordPress, don't worry! I will guide you through the installation and use of the web page step-by-step. If you are an expert, you can directly jump to Step 2! Thank you for your patience, enjoy it!
<br></br>

# Step 1: install WordPress.org
<br></br>

## pre-requisite
- XAMPP -> [Download](https://www.apachefriends.org/download.html)
- WordPress -> [Download](https://wordpress.org/)
<br></br>

## setup localhost
1. open XAMPP, you can see the light of **Status** is **yellow** or **red**, this means the server is down at the moment
2. click **Start** and wait for a second until the light turns to **green**
3. click **Service**, you can see 3 stack services: Apache, MySQL and ProFTPD. Click **Start All** if the lights are **red**
4. click **Network**, there should be 2 localhosts, enable a host for your project (normally enable localhost:8080 -> 80)
5. click **volumes**, the directory(/opt/lampp) shown is the root of server, then click **Mount** to activate it
6. click **Explore** you can open the root directory, find and open the folder named **htdocs**
<br></br>
## buildup local database
1. open your browser(Chrome or FireFox recommended) and visit ```http://localhost:xxxx/phpmyadmin/``` (**replace xxxx with the port number enabled in XAMPP**)
2. then you will be directed to **phpMyAdmin**, the panel where you can modify your local database
3. extract the zip file of wordpress you just downloaded, and you can get a folder **wordpress**
4. copy and paste **wordpress** into **htdocs**
5. click Add user and choose a username for WordPress and enter it in the **User name** field
6. choose a secure password and enter it in the **Password** field, re-enter the password in the Re-type field
7. leave all options under **Global privileges** at their defaults
8. click **Go**
<br></br>
## install WordPress
1. visit ```http://localhost:xxxx/wp-admin/install.php```
2. wordpress asks for the information about the **database**
3. fill the form (do not need to change prefix if there is only one database for wordpress)
4. create a file **wp-conf.php**, or you can just modify **wp-conf-sample.php**, fill database info with yours and save it
5. reopen ```http://localhost:xxxx/wordpress```, you can now register an account for your website
6. after registration, you can access web page with your account!

<br></br>

# Step 2: mirate our live web app to local host
<br></br>

## pre-requisite:

[**ta28.sql**](https://github.com/VictorW010/DroughtWise/blob/main/ta28.sql)

[**wp-content**](https://github.com/VictorW010/DroughtWise/tree/main/wwwroot/wp-content)
<br></br>
## database migration

1. create the database that was created in Step 1
2. select all tables, click **dropdown list** and select **drop**
3. click Go
4. click **Import** in the top menu (make sure you are using the correct database!)
5. click choose file and select **ta28.sql** you downloaded from our git
6. keep all things unchanged and click Go
7. then all tables will be migrated to your local database successfully (but not finish yet!!)
8. click table **wp_options**
9. click **edit** in the **first record**
10. in **option_value** section, change text to ```http://localhost:8080/wordpress``` (depends on your settings), then click Go
11. do the same operation to the **second record**
12. this makes sure the route is correct on local host
<br></br>
## content migration
1. copy and paste **wp-content** to **htdocs/wordpress/**
2. **replace** the original wp-content with ours
3. in current location ```chmod -R 777 wp-content```
4. Done! just access via ```http://localhost:8080/wordpress/``` (depends on your settings)
5. USE ```123``` AS PASSWORD TO LOGIN


<br></br>
# Where are the files?

- wp-content/uploads/2021/08 contains all the pictures loaded in the web app
- as WordPress loads pages dynamically, it stored **all the posts in the database**
  - all **pages** are stored in table **wp_posts**
  - all **PHP code** are stored in table **wp_xyz_ips_short_code** (XYZ PHP Code is a plugin that provides PHP snippets in which we can write our code, then the code will be called by adding a "shortcode" section into the page
  - table **wp_services** is used to store required information so that we could use php functions to query from backend

- CSS files are directly written in the **Additional CSS**, which can be accessed by clicking **Customise** at top of the screen (needs to be in the edit mode first)

<br></br>
# How to Edit and Debug?
1. for editing, go to ```http://localhost:8080/wordpress/wp-admin/``` (depends on your settings)
2. login with **the account you registered in Step 1 -> install WordPress -> 5**.
3. after login, you can play around in **dashboard** or just go back to ```http://localhost:8080/wordpress/``` (depends on your settings)
4. now at the top you can see some options to edit the page!
5. click customize page, your are able to edit **header**, **footer** and create your own CSS code in **Additional CSS**
6. if you want to edit the body, just find the corresponding table in **database -> wp_posts**
7. an easy way to find all pages is to execute this statement:
```sql
SELECT * FROM `wp_posts` WHERE post_status = 'publish';
```
8. then you can click **Edit** to modify them
9. also, in the dashboard, there might be some warnings, this is because our project was migrated from Azure Cloud Server, some theme elements may not work properly. But with those warnings you can easily track reasons, usually they are not fatal.
10. ```chmod 777 wordpress``` (where wordpress is the root folder) helps a lot to get permissions.
11. also, please add following code to **wp-conf.php**:
```php
define( 'FS_METHOD', 'direct' );
```
12.if some of the pictures can not be properly loaded, try elevating privilege via ```chmod -R 777 filename``` 
<br></br>
<br></br>
# Thanks for your reading, hope you have a good time!
<br></br>
Please feel free to [contact us](https://mahara.infotech.monash.edu/group/view.php?id=1930) if you have any question!

