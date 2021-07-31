<?php

namespace Contentify\Vendor\Rss;

class Rss {
    /** @var string */
    protected $version = '';

    /** @var string */
    protected $encoding = '';

    /** @var array */
    protected $channel = array();

    /** @var array */
    protected $items = array();

    /** @var int */
    protected $limit = 0;

    /**
     * Setup the feed
     *
     * @param string $version
     * @param string $encoding
     * @return $this
     */
    public function feed(string $version, string $encoding) : Rss
    {
        $this->version  = $version;
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * Define a channel
     *
     * Parameters :
     * - title (required)
     * - link (required)
     * - description (required)
     * - language
     * - copyright
     * - managingEditor
     * - webMaster
     * - pubDate
     * - lastBuildDate
     * - category
     * - generator
     * - docs
     * - cloud
     * - ttl
     * - image
     * - rating
     * - textInput
     * - skipHours
     * - skipDays
     *
     * @param $parameters
     * @return Rss
     * @throws \Exception
     */
    public function channel($parameters) : Rss
    {
        if (!array_key_exists('title', $parameters) || !array_key_exists('description', $parameters) || !array_key_exists('link', $parameters))
        {
            throw new \Exception('Parameter required missing : title, description or link');
        }

        $this->channel = $parameters;

        return $this;
    }

    /**
     * Add an item
     *
     * Parameters :
     * - title
     * - link
     * - description
     * - author
     * - category
     * - comments
     * - enclosure
     * - guid
     * - pubDate
     * - source
     *
     * @param $parameters
     * @return Rss
     * @throws \Exception
     */
    public function item($parameters) : Rss
    {
        if (empty($parameters))
        {
            throw new \Exception('Parameter missing');
        }

        $this->items[] = $parameters;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit) : Rss
    {
        if ($limit > 0)
        {
            $this->limit = $limit;
        }

        return $this;
    }

    /**
     * Create actual XML code
     *
     * @return SimpleXMLElement
     * @throws \Exception
     */
    public function render(): SimpleXMLElement
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="'.$this->encoding.'"?><rss version="'.$this->version.'" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/"></rss>', LIBXML_NOERROR|LIBXML_ERR_NONE|LIBXML_ERR_FATAL);

        $xml->addChild('channel');

        foreach ($this->channel as $kC => $vC)
        {
            $xml->channel->addChild($kC, $vC);
        }

        $items = $this->limit > 0 ? array_slice($this->items, 0, $this->limit) : $this->items;

        foreach ($items as $item)
        {
            $elem_item = $xml->channel->addChild('item');

            foreach ($item as $kI => $vI)
            {
                $options = explode('|', $kI);

                if (in_array('cdata', $options))
                {
                    $elem_item->addCdataChild($options[0], $vI);
                }
                elseif (strpos($options[0], ':') !== false)
                {
                    $elem_item->addChild($options[0], $vI, 'http://purl.org/dc/elements/1.1/');
                }
                else
                {
                    $elem_item->addChild($options[0], $vI);
                }
            }
        }

        return $xml;
    }

    /**
     * Return a well-formed XML string based on SimpleXML element
     *
     * @param string $filename
     * @return bool|string
     * @throws \Exception
     */
    public function save(string $filename)
    {
        return $this->render()->asXML($filename);
    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    public function __toString()
    {
        return $this->render()->asXML();
    }
}
