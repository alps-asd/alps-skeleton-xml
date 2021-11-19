<?php

declare(strict_types=1);

namespace Koriym\DataFile;

use DOMDocument;
use Koriym\DataFile\Exception\DataFileException;
use Koriym\DataFile\Exception\DataFileNotFoundException;
use SimpleXMLElement;

use function assert;
use function file_exists;
use function file_get_contents;
use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_use_internal_errors;
use function simplexml_load_string;
use function sprintf;
use function substr;

use const LIBXML_ERR_ERROR;
use const LIBXML_ERR_FATAL;

final class XmlLoad
{
    public function __invoke(string $xmlPath, string $xsdPath): SimpleXMLElement
    {
        if (! file_exists($xmlPath)) {
            throw new DataFileNotFoundException($xmlPath);
        }

        $this->validate($xmlPath, $xsdPath);
        $contents = (string) file_get_contents($xmlPath);
        $simpleXml = simplexml_load_string($contents);
        assert($simpleXml instanceof SimpleXMLElement);

        return $simpleXml;
    }

    private function validate(string $xmlPath, string $xsdPath): void
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->load($xmlPath);
        if ($dom->schemaValidate($xsdPath)) {
            return;
        }

        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            if ($error->level === LIBXML_ERR_FATAL || $error->level === LIBXML_ERR_ERROR) {
                libxml_clear_errors();

                $msg = sprintf('%s in %s:%s', substr($error->message, 0, -2), $error->file, $error->line);

                throw new DataFileException($msg);
                // @codeCoverageIgnoreStart
            }
        }
    }

    // @codeCoverageIgnoreEnd
}
