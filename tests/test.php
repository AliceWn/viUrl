<?php
/*
# Copyright (C) 2014 Radoslav Vitanov
#
# Permission is hereby granted, free of charge, to any person obtaining a copy of this 
# software and associated documentation files (the "Software"), to deal in the Software 
# without restriction, including without limitation the rights to use, copy, modify, merge, 
# publish, distribute, sublicense, and/or sell copies of the Software, and to permit 
# persons to whom the Software is furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all copies or 
# substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING 
# BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
# DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
use Vi\Url\Url;
require_once '../include/Vi/Exception/UrlException.php';
require_once '../include/Vi/Url/Url.php';

$url = new Url('http://test.example.com/test1/test2');

// This will output - http://test.example.com/test1/test2
echo $url->buildUrl();

// Let's force WWW - http://www.test.example.com/test1/test2
echo $url->setForceWWW(true)->buildUrl();

// You can change scheme - https://www.test.example.com/test1/test2
echo $url->setScheme('https')->buildUrl();

// You can append and prepend parts to the path - https://www.test.example.com/test1/test3/test2
echo $url->insertPathAfter('test1', 'test3')->buildUrl();

// https://www.test.example.com/test1/test4/test3/test2
echo $url->insertPathBefore('test3', 'test4')->buildUrl();

// You can also replace or delete paths - https://www.test.example.com/test1/test4/test3/new
echo $url->replacePath('test2', 'new')->buildUrl();

// https://www.test.example.com/test4/test3/new
echo $url->deltePath('test1')->buildUrl();

// You can also assign query parameters - https://www.test.example.com/test4/test3/new?key1=value1&key2=value2
echo $url->setQueryParams(array('key1' => 'value1', 'key2' => 'value2'))->buildUrl();

// You can manipulate query parameters aswell - https://www.test.example.com/test4/test3/new?key1=testValue&key2=value2
echo $url->setQueryParam('key1', 'testValue')->buildUrl();

// https://www.test.example.com/test4/test3/new?key1=testValue
echo $url->removeQueryParam('key2')->buildUrl();

// You can also add fragment to the url - https://www.test.example.com/test4/test3/new?key1=testValue#randomHash
echo $url->setFragment('randomHash')->buildUrl();

// Add a port - https://www.test.example.com:8888/test4/test3/new?key1=testValue#randomHash
echo $url->setPort(8888)->buildUrl();