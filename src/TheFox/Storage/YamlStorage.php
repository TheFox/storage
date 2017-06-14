<?php

namespace TheFox\Storage;

use Symfony\Component\Yaml\Yaml;

class YamlStorage
{
    /**
     * @var string
     * @deprecated Will be removed in 1.0.0.
     */
    private $datadirBasePath = '';

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @todo make private
     * @var bool
     */
    public $dataChanged = false;

    /**
     * @var bool
     */
    private $isLoaded = false;

    /**
     * YamlStorage constructor.
     *
     * @param string|null $filePath
     */
    public function __construct(string $filePath = null)
    {
        if ($filePath !== null) {
            $this->setFilePath($filePath);
        }
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->dataChanged) {
            return false;
        }

        if (!$this->getFilePath()) {
            return false;
        }

        $yaml = Yaml::dump($this->data);
        $rv = file_put_contents($this->getFilePath(), $yaml);
        if (!$rv) {
            return false;
        }

        $this->setDataChanged(false);
        return true;
    }

    /**
     * @return bool
     */
    public function load(): bool
    {
        if ($this->getFilePath()) {
            if (file_exists($this->getFilePath())) {
                // Read file content.
                $content = file_get_contents($this->getFilePath());

                // Parse file content.
                $this->data = Yaml::parse($content);

                return $this->isLoaded(true);
            }
        }

        return false;
    }

    /**
     * @param bool|null $isLoaded
     * @return bool
     */
    public function isLoaded(bool $isLoaded = null): bool
    {
        if ($isLoaded !== null) {
            $this->isLoaded = $isLoaded;
        }

        return $this->isLoaded;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @deprecated Will be removed in 1.0.0.
     * @param string $datadirBasePath
     */
    public function setDatadirBasePath(string $datadirBasePath)
    {
        $this->datadirBasePath = $datadirBasePath;
    }

    /**
     * @deprecated Will be removed in 1.0.0.
     * @return string
     */
    public function getDatadirBasePath(): string
    {
        return $this->datadirBasePath;
    }

    /**
     * @deprecated Will be made to prodected in 1.0.0.
     * @param bool $changed
     */
    public function setDataChanged(bool $changed = true)
    {
        $this->dataChanged = $changed;
    }

    /**
     * @return bool
     */
    public function getDataChanged(): bool
    {
        return $this->dataChanged;
    }
}
