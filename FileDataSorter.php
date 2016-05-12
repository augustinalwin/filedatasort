<?php

/**
 * Class FileDataSorter
 */
class FileDataSorter
{

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var array
     */
    private $fileData = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    private $formattedData;


    /**
     * FileDataSorter constructor.
     * @param $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function getFileData()
    {
        if (!is_readable($this->fileName)) {

            throw new Exception('Not able to read file. Exiting');
        }

        $handle = fopen($this->fileName, 'r');

        while (($data = fgetcsv($handle, 1000, "\t")) !== false) {
            $this->fileData[] = $data;
        }

        if (empty($this->fileData)) {
            throw new Exception('No data in th file. Exiting');
        }

        return $this;
    }

    /**
     * @param $column
     * @param $order
     * @return $this
     */
    public function sort($column, $order)
    {
        $sortOrder     = $this->getSortOrder($order);
        $this->headers = $this->fileData[0];
        $columnIndex   = array_search($column, $this->headers);

        unset($this->fileData[0]);

        foreach ($this->fileData as $data) {

            $sortBase[] = $data[$columnIndex];
        }

        array_multisort($sortBase, $sortOrder, $this->fileData);

        return $this;

    }

    /**
     *
     */
    public function display()
    {
        //Adding headers
        $this->formattedData .= implode("\t", $this->headers);
        $this->formattedData .= "\n";

        //Adding other rows
        foreach ($this->fileData as $data) {
            $this->formattedData .= implode("\t", $data);
            $this->formattedData .= "\n";
        }

        echo $this->formattedData;
    }

    /**
     * @param $order
     * @return int
     */
    private function getSortOrder($order)
    {
        return !empty($order) && $order == 'desc'
            ? SORT_DESC
            : SORT_ASC;
    }
}
