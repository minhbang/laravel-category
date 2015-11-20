# Laravel Category

## Install

* **Thêm vào file composer.json của app**
```json
	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/minhbang/laravel-category"
        }
    ],
    "require": {
        "minhbang/laravel-category": "dev-master"
    }
```
``` bash
$ composer update
```

* **Thêm vào file config/app.php => 'providers'**
```php
	Minhbang\Category\ServiceProvider::class,
```

* **Publish config và database migrations**
```bash
$ php artisan vendor:publish
$ php artisan migrate
```

## Thêm loại Category mới

* **config:** category.php
```php
'types' => ['article', 'new_type'],
```

* **lang:** type.php
```php
'new_type' => 'Loại mới'
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
