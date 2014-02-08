<?php

namespace Reader\Bundle\ReaderBundle\Document;

/**
 * Class Story
 * @package Reader\Bundle\ReaderBundle\Document
 */
class Story
{
    const IMAGES_DIR = '/uploads/images';

    protected $text;
    protected $title;
    protected $textSum;
    protected $site;
    protected $grabbed;
    protected $page;
    protected $position;
    protected $image;
    protected $temp;
    protected $randomizer;
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
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
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
     * @param string $imageUrl
     * @return self
     */
    public function setImage($imageUrl)
    {
        $this->image = $this->downloadImage($imageUrl);
        return $this;
    }

    /**
     * Get image
     *
     * @return string $image
     */
    public function getImage()
    {
        return $this->getImagePath();
    }

    /**
     * Get absolute image path
     *
     * @return null|string
     */
    protected function getAbsoluteImagePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir().'/'.$this->image;
    }

    /**
     * Get image path
     *
     * @return null|string
     */
    protected function getImagePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadDir().'/'.$this->image;
    }


    /**
     * Get upload root directory for image
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web'.$this->getUploadDir();
    }

    /**
     * Get upload directory for image
     *
     * @return string
     */
    protected function getUploadDir()
    {
        return self::IMAGES_DIR;
    }

    /**
     * Download url to upload directory
     *
     * @param $url
     * @return string
     */
    protected function downloadImage( $url )
    {
        $saveDir  = $this->getUploadRootDir();
        $fileName = sha1( $url );
        $filePath = sprintf('%s/%s', $saveDir, $fileName );

        try {
            $ch = curl_init( $url );
            $fp = fopen( $filePath, 'wb' );
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        } catch (\Exception $e) {
        }

        return $fileName;
    }

    /**
     * Store file path to remove before remove
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsoluteImagePath();
    }

    /**
     * Remove associated image after remove story
     */
    public function removeUpload()
    {
        if ( isset($this->temp) && file_exists( $this->temp ) ) {
            unlink($this->temp);
        }
    }

    /**
     * @return mixed
     */
    public function getRandomizer()
    {
        return $this->randomizer;
    }

    /**
     * @param Randomizer
     */
    public function setRandomizer()
    {
        $this->randomizer = array( (float) lcg_value(), (float) 0);
    }
}
