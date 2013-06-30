<?php

namespace Reader\Bundle\ReaderBundle\Document;

class Story
{
    protected $text;
    protected $textSum;
    protected $site;
    protected $grabbed;
    protected $page;
    protected $position;
    protected $image;
    /**
     * @var \MongoId $id
     */
    protected $id;


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
     * Set text
     *
     * @param string $text
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set textSum
     *
     * @param string $textSum
     * @return self
     */
    public function setTextSum($textSum)
    {
        $this->textSum = $textSum;
        return $this;
    }

    /**
     * Get textSum
     *
     * @return string $textSum
     */
    public function getTextSum()
    {
        return $this->textSum;
    }

    /**
     * Set grabbed
     *
     * @param \MongoTimestamp $grabbed
     * @return self
     */
    public function setGrabbed($grabbed)
    {
        $this->grabbed = $grabbed;
        return $this;
    }

    /**
     * Get grabbed
     *
     * @return \MongoTimestamp $grabbed
     */
    public function getGrabbed()
    {
        return $this->grabbed;
    }

    /**
     * Set site
     *
     * @param Site $site
     * @return self
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * Get site
     *
     * @return Site $site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set page
     *
     * @param int $page
     * @return self
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Get page
     *
     * @return int $page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set position
     *
     * @param int $position
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     *
     * @return int $position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string $image
     */
    public function getImage()
    {
        return $this->image;
    }
}
