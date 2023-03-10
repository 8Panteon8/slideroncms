<?php namespace Winter\Storm\Parse\Assetic\Filter;

use Less_Parser;
use Assetic\Filter\BaseFilter;
use Assetic\Filter\LessphpFilter;
use Assetic\Factory\AssetFactory;
use Assetic\Contracts\Asset\AssetInterface;
use Assetic\Contracts\Filter\HashableInterface;
use Assetic\Contracts\Filter\DependencyExtractorInterface;

/**
 * Less.php Compiler Filter
 * Class used to compile LESS stylesheet files into CSS
 *
 * @link https://github.com/wikimedia/less.php
 *
 * @author Alexey Bobkov, Samuel Georges
 */
class LessCompiler extends BaseFilter implements HashableInterface, DependencyExtractorInterface
{
    protected $presets = [];

    protected $lastHash;

    public function setPresets(array $presets)
    {
        $this->presets = $presets;
    }

    public function filterLoad(AssetInterface $asset)
    {
        $parser = new Less_Parser();

        // CSS Rewriter will take care of this
        $parser->SetOption('relativeUrls', false);

        $parser->parseFile($asset->getSourceRoot() . '/' . $asset->getSourcePath());

        // Set the LESS variables after parsing to override them
        $parser->ModifyVars($this->presets);

        $asset->setContent($parser->getCss());
    }

    public function hashAsset($asset, $localPath)
    {
        $factory = new AssetFactory($localPath);
        $children = $this->getChildren($factory, file_get_contents($asset), dirname($asset));

        $allFiles = [];
        foreach ($children as $child) {
            $allFiles[] = $child;
        }

        $modifieds = [];
        foreach ($allFiles as $file) {
            $modifieds[] = $file->getLastModified();
        }

        return md5(implode('|', $modifieds));
    }

    public function setHash($hash)
    {
        $this->lastHash = $hash;
    }

    /**
     * Generates a hash for the object
     * @return string
     */
    public function hash()
    {
        return $this->lastHash ?: serialize($this);
    }

    /**
     * Load children recusive
     */
    public function getChildren(AssetFactory $factory, $content, $loadPath = null)
    {
        $children = (new LessphpFilter)->getChildren($factory, $content, $loadPath);

        foreach ($children as $child) {
            $childContent = file_get_contents($child->getSourceRoot().'/'.$child->getSourcePath());
            $children = array_merge($children, (new LessphpFilter)->getChildren($factory, $childContent, $loadPath.'/'.dirname($child->getSourcePath())));
        }

        return $children;
    }
}
