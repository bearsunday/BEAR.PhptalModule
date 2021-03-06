<?php
/**
 * This file is part of the BEAR.Package package
 *
 * @package BEAR.PhptalModule
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\PhptalModule\Provide\TemplateEngine\Phptal;

use BEAR\Sunday\Extension\TemplateEngine\TemplateEngineAdapterInterface;
use BEAR\Sunday\Exception\TemplateNotFound;
use PHPTAL;
use Ray\Di\Di\Inject;

/**
 * PHPTAL adapter
 *
 */
class PhptalAdapter implements TemplateEngineAdapterInterface
{
    /**
     * File extension
     *
     * @var string
     */
    const EXT = 'xhtml';

    /**
     * PHPTAL
     *
     * @var PHPTAL
     */
    private $phptal;

    /**
     * Template file
     *
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $values;

    /**
     * Constructor
     *
     * @param PHPTAL $phptal
     *
     * @Inject
     */
    public function __construct(PHPTAL $phptal)
    {
        $this->phptal = $phptal;
    }

    /**
     * Return file exists
     *
     * @param string $template
     *
     * @throws TemplateNotFound
     */
    private function fileExists($template)
    {
        if (!file_exists($template)) {
            throw new TemplateNotFound($template);
        }
    }

    /**
     * Return template full path.
     *
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function assign($tplVar, $value)
    {
        $this->values[$tplVar] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function assignAll(array $values)
    {
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($tplWithoutExtension)
    {
        $this->template = $tplWithoutExtension . self::EXT;
        $this->fileExists($this->template);
        $this->phptal->setTemplate($this->template);
        foreach ($this->values as $k => $v) {
            $this->phptal->set($k, $v);
        }
        $rendered = $this->phptal->execute();
        return $rendered;
    }
}
