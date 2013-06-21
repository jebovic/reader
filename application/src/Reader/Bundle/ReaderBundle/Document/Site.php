<?php

namespace Reader\Bundle\ReaderBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Reader\Bundle\ReaderBundle\Document\Category;

class Site
{
    protected $identifier;
    protected $title;
    protected $shortTitle;
    protected $url;
    protected $urlPattern;
    protected $urlFirstPage;
    protected $urlStep;
    protected $grabSelector;
    protected $allowedTags;
    protected $imageTag;
    protected $categories;
    protected $featured;
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

}
