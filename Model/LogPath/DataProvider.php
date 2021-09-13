<?php

namespace CodeCustom\PureLogViewer\Model\LogPath;

use Magento\Ui\DataProvider\AbstractDataProvider;
use CodeCustom\PureLogViewer\Model\ResourceModel\LogPath\Collection as LogPathCollection;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var LogPathCollection
     */
    protected $collection;

    /*
     * Load data from colection
     */
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        LogPathCollection $collection,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $collection;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if(isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems();

        foreach($items as $contact)
        {
            $this->_loadedData[$contact->getId()] = $contact->getData();
        }

        return $this->_loadedData;
    }
}
