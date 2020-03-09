<!-- ## CxEngine cloud
W.I.P. -->

## Apache / PHP server
- Copy all the files

- Create a .htaccess with the following content:
```
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

### Running in a subfolder
- Change the basePath in `index.php`:
```
$app->set('basePath', '/yourapp');
```
- Modify the `.htaccess`:
```
RewriteEngine On
RewriteBase /yourapp
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```
