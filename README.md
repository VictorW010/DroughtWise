# DroughtWise

### How to migrate a live site to localhost

1. pre-requisite: XAMPP
2. make sure MySQL installed
3. PHP 7 or upper
4. create an empty folder and pull the project from git
5. copy and paste **wp-content, wp-admin, wp-includes**
6. in phpadmin create a database named the same to the name in **wp-config.php**
7. copy the .crt and .key files into the Apache folder to install the same certificate on the local site
8. then follow this link to migrate our site: https://website-overnight.com/migrate-a-live-wordpress-site-to-localhost-and-install-its-ssl-certificate-on-xampp/#Installing_SSL_on_a_Local_WordPress_Site
9. **ta28.sql is the statement that helps to migrate the database.** In phpAdmin click on "import" to execute the file.
10. then, in table wp-options change the "siteurl" to localhost



### Where are the files

- Wp-content/uploads/2021/08 contains all the pictures loaded in the web app
- as WordPress loads pages dynamically, it stored **all the posts in the database**
- **Plugins are used for easily coding:**
  - XYZ PHP Code: this plugin provides PHP snippet that allows us to program in, then add a shortcode into a certain section of the page. (the code has also been put into a separated file on git)
  - Custom CSS & JS: this plugin is for coding HTML, CSS and JS codes.
