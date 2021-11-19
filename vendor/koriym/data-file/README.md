# Koriym.DataFile
[![Continuous Integration](https://github.com/koriym/Koriym.DataFile/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/koriym/Koriym.DataFile/actions/workflows/continuous-integration.yml)

Validate and load the XML file.

## Installation

    composer require koriym/data-file

## Usage

Simple XML load with validation

```php
use Koriym\DataFile\XmlLoad;

$xml = (new XmlLoad())('/path/to/xml', '/path/to/xsd');
assert($xml instanceof SimpleXMLElement);
```

Config xml load

```php
use Koriym\DataFile\XmlConfigLoad;

$xml = (new XmlConfigLoad('confilg.xml'))('/path/to/config_dir', '/path/to/xsd');
assert($xml instanceof SimpleXMLElement);
```

Loads the `config.xml` or `config.xml.dist` of the specified directory.
`config.xml` will be loaded first.

It is common to ignore dist files to save them in the repository and not to save the local files.
