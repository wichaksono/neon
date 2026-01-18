## Hidden WP Admin Menus

### How To Use
```php
require_once 'WPAdminMenuManager.php'; // sesuaikan dengan lokasi

$adminMenu = new \NeonWebId\Utils\WPAdminMenuManager();

$adminMenu->hiddenMenu('edit-comments.php');
$adminMenu->hiddenMenu('plugins.php', ['plugin-editor.php']);
$adminMenu->hiddenMenu('index.php', ['update-core.php']);

$adminMenu->allowByUsername(['admin']);
$adminMenu->allowByEmail(['admin@example.com']);
```
