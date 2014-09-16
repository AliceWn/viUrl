viUrl
=====

viUrl is class wrapper for object oriented way of building url's.

Current version 1.0.

## Usage

```php
$url = new viUrl('http://test.example.com/test1/test2');
```

```php
// This will output - http://test.example.com/test1/test2
echo $url->buildUrl();
```

```php
// Let's force WWW - http://www.test.example.com/test1/test2
echo $url->setForceWWW(true)->buildUrl();
```

```php
// You can change scheme - https://www.test.example.com/test1/test2
echo $url->setScheme('https')->buildUrl();
```

```php
// You can append and prepend parts to the path - https://www.test.example.com/test1/test3/test2
echo $url->insertPathAfter('test1', 'test3')->buildUrl();
```

```php
// https://www.test.example.com/test1/test4/test3/test2
echo $url->insertPathBefore('test3', 'test4')->buildUrl();
```

```php
// You can also replace or delete paths - https://www.test.example.com/test1/test4/test3/new
echo $url->replacePath('test2', 'new')->buildUrl();
```

```php
// https://www.test.example.com/test4/test3/new
echo $url->deltePath('test1')->buildUrl();
```

```php
// You can also assign query parameters - https://www.test.example.com/test4/test3/new?key1=value1&key2=value2
echo $url->setQueryParams(array('key1' => 'value1', 'key2' => 'value2'))->buildUrl();
```

```php
// You can manipulate query parameters aswell - https://www.test.example.com/test4/test3/new?key1=testValue&key2=value2
echo $url->setQueryParam('key1', 'testValue')->buildUrl();
```

```php
// https://www.test.example.com/test4/test3/new?key1=testValue
echo $url->removeQueryParam('key2')->buildUrl();
```

```php
// You can also add fragment to the url - https://www.test.example.com/test4/test3/new?key1=testValue#randomHash
echo $url->setFragment('randomHash')->buildUrl();
```

```php
// Add a port - https://www.test.example.com:8888/test4/test3/new?key1=testValue#randomHash
echo $url->setPort(8888)->buildUrl();
```

## License

viUrl is licenced under the [MIT License] (http://opensource.org/licenses/MIT).