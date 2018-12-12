# kjBot CoreModule

```xml
<project path="modules/kj415j45/CoreModule/" name="CoreModule" remote="kjBot-Modules" revision="0.x" />
```

## AccessControl

### modules.php

```php
'权限控制' => kjBotModule\kj415j45\CoreModule\AccessControlModule::class,
```

### plugins.php

```php
//放在第一位
kjBotModule\kj415j45\CoreModule\AccessControlPlugin::class,
```

### config.ini

```ini
ACLevel=1 #Bot处理该消息所需的最低权限等级
```