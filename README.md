# Google Chrome Coverage CSS extrator
 A small script in PHP to extract the CSS from json created by the Coverage tab in Google Chrome

## How to find unused CSS in your project
1. Open your project in Google Chrome
2. Open the DevTools (F12)
3. Go to the Coverage tab
4. Click on the "Start" button
5. Make sure that the all the CSS files are loaded
6. Activate all the elements in the page (click on them, scroll, etc)
7. Click on the "Stop" button
8. Click on the "Save as" button
9. Save the file as `coverage.json`
10. Run the script `php coverage.php`
11. The script will create a file called `coverage.css` with all the unused CSS

## How to use the script
1. Download the script
2. Open through a local server (XAMPP, MAMP, etc) or through the command line `php coverage.php`
3. The script will create a file called `coverage.css` with all the unused CSS