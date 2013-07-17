<?php

namespace Oneup\UploaderBundle\Uploader\Naming;

use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FormatNamer implements NamerInterface
{
    /** @var string */
    protected $formatString;
    /** @var array */
    protected $placeholders;

    /**
     * @param $formatString
     */
    public function __construct($formatString)
    {
        $this->formatString = $formatString;
        $this->placeholders = preg_match_all('/\{([a-z0-9_\-\.]+)\}/i', $formatString, $matches)
            ? $matches[1]
            : array();
    }

    /**
     * @inheritdoc
     */
    public function name(UploadedFile $file, Request $request)
    {
        $result = $this->formatString;
        foreach ($this->placeholders as $placeholder) {
            $result = $this->getRequestParamByPlaceholderName($result, $placeholder, $request);
        }
        return sprintf('%s.%s', $result, $file->guessExtension());
    }

    protected function getRequestParamByPlaceholderName($string, $placeholder, Request $request)
    {
        $placeholderStr = '{' . $placeholder . '}';
        $nameParts = explode('.', $placeholder);
        $fieldName = $nameParts[0];
        $nestedNames = count($nameParts) > 1 ? array_slice($nameParts, 1) : array();
        $field = $request->request->get($fieldName);
        if (!$field) {
            return str_replace($placeholderStr, $this->getFallbackPlaceholderName($placeholder), $string);
        }
        if ($nestedNames) {
            foreach ($nestedNames as $name) {
                if (!is_array($field) || !array_key_exists($name, $field)) {
                    return str_replace($placeholderStr, $this->getFallbackPlaceholderName($placeholder), $string);
                }
                $field = $field[$name];
            }
        }
        return is_string($field) || is_numeric($field)
            ? str_replace($placeholderStr, $field, $string)
            : str_replace($placeholderStr, $this->getFallbackPlaceholderName($placeholder), $string);
    }

    protected function getFallbackPlaceholderName($placeholder)
    {
        return str_replace('.', '_', $placeholder);
    }
}
