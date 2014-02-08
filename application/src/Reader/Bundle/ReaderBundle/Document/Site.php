<?php

namespace Reader\Bundle\ReaderBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Reader\Bundle\ReaderBundle\Document\Category;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Site
{
    const LOGOS_DIR = '/uploads/logos';
    protected $identifier;
    protected $title;
    protected $shortTitle;
    protected $url;
    protected $urlPattern;
    protected $urlFirstPage;
    protected $urlStep;
    protected $grabSelector;
    protected $titleSelector;
    protected $contentSelector;
    protected $allowedTags;
    protected $imageTag;
    protected $categories;
    protected $featured;
    protected $logo;
    protected $logoPath;
    protected $logoTemp;
    protected $color;
    /**
     * @var \MongoId $id
     */
    protected $id;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return \MongoId $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return self
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Get identifier
     *
     * @return string $identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set shortTitle
     *
     * @param string $shortTitle
     * @return self
     */
    public function setShortTitle($shortTitle)
    {
        $this->shortTitle = $shortTitle;
        return $this;
    }

    /**
     * Get shortTitle
     *
     * @return string $shortTitle
     */
    public function getShortTitle()
    {
        return $this->shortTitle;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set urlPattern
     *
     * @param string $urlPattern
     * @return self
     */
    public function setUrlPattern($urlPattern)
    {
        $this->urlPattern = $urlPattern;
        return $this;
    }

    /**
     * Get urlPattern
     *
     * @return string $urlPattern
     */
    public function getUrlPattern()
    {
        return $this->urlPattern;
    }

    /**
     * Set urlFirstPage
     *
     * @param int $urlFirstPage
     * @return self
     */
    public function setUrlFirstPage($urlFirstPage)
    {
        $this->urlFirstPage = $urlFirstPage;
        return $this;
    }

    /**
     * Get urlFirstPage
     *
     * @return int $urlFirstPage
     */
    public function getUrlFirstPage()
    {
        return $this->urlFirstPage;
    }

    /**
     * Set urlStep
     *
     * @param int $urlStep
     * @return self
     */
    public function setUrlStep($urlStep)
    {
        $this->urlStep = $urlStep;
        return $this;
    }

    /**
     * Get urlStep
     *
     * @return int $urlStep
     */
    public function getUrlStep()
    {
        return $this->urlStep;
    }

    /**
     * Set grabSelector
     *
     * @param string $grabSelector
     * @return self
     */
    public function setGrabSelector($grabSelector)
    {
        $this->grabSelector = $grabSelector;
        return $this;
    }

    /**
     * Get grabSelector
     *
     * @return string $grabSelector
     */
    public function getGrabSelector()
    {
        return $this->grabSelector;
    }

    /**
     * @param mixed $contentSelector
     */
    public function setContentSelector($contentSelector)
    {
        $this->contentSelector = $contentSelector;
    }

    /**
     * @return mixed
     */
    public function getContentSelector()
    {
        return $this->contentSelector;
    }

    /**
     * @param mixed $titleSelector
     */
    public function setTitleSelector($titleSelector)
    {
        $this->titleSelector = $titleSelector;
    }

    /**
     * @return mixed
     */
    public function getTitleSelector()
    {
        return $this->titleSelector;
    }

    /**
     * Set allowedTags
     *
     * @param string $allowedTags
     * @return self
     */
    public function setAllowedTags($allowedTags)
    {
        $this->allowedTags = $allowedTags;
        return $this;
    }

    /**
     * Get allowedTags
     *
     * @return string $allowedTags
     */
    public function getAllowedTags()
    {
        return $this->allowedTags;
    }

    /**
     * Add categories
     *
     * @param Category $category
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set imageTag
     *
     * @param string $imageTag
     * @return self
     */
    public function setImageTag($imageTag)
    {
        $this->imageTag = $imageTag;
        return $this;
    }

    /**
     * Get imageTag
     *
     * @return string $imageTag
     */
    public function getImageTag()
    {
        return $this->imageTag;
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     * @return self
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean $featured
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setLogoPath( $path )
    {
        $this->logoPath = $path;
        return $this;
    }

    /**
     * Get logo path.
     *
     * @return UploadedFile
     */
    public function getLogoPath()
    {
        return $this->getWebPath();
    }

    /**
     * @param UploadedFile $file
     * @return $this
     */
    public function setLogo(UploadedFile $file = null)
    {
        $this->logo = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->logoTemp = $this->getAbsolutePath();
        } else {
            $this->logoPath = 'initial';
        }
        return $this;
    }

    /**
     * Get logo.
     *
     * @return UploadedFile
     */
    public function getLogo()
    {
        return $this->logo;
    }

    public function preUpload()
    {
        if (null !== $this->getLogo()) {
            $this->logoPath = $this->getLogo()->guessExtension();
        }
    }

    public function upload()
    {
        if (null === $this->getLogo()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $fileName = $this->id.'.'.$this->getLogo()->guessExtension();
        $this->getLogo()->move(
            $this->getUploadRootDir(),
            $fileName
        );

        $this->setLogo( null );
        $this->setLogoPath( $fileName );
    }

    public function storeFilenameForRemove()
    {
        $this->logoTemp = $this->getAbsolutePath();
    }

    public function removeUpload()
    {
        if (isset($this->logoTemp)) {
            unlink($this->logoTemp);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->logoPath
            ? null
            : $this->getUploadRootDir().'/'.$this->id.'.'.$this->logoPath;
    }

    public function getWebPath()
    {
        return null === $this->logoPath
            ? null
            : $this->getUploadDir().'/'.$this->logoPath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return self::LOGOS_DIR;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }
}
