<?php

namespace Crew\Unsplash;

class CuratedBatch extends Endpoint
{
    private $photos;
    private $curator;

    /**
     * Retrieve a specific curated batch
     * 
     * @param  int $id Id of the curated batch
     * @return CuratedBatch
     */
    public static function find($id)
    {
        $curatedBatch = json_decode(self::get("curated_batches/{$id}")->getBody(), true);
        
        return new self($curatedBatch);
    }

    /**
     * Retrieve all curated batches for a given page
     * 
     * @param  integer $page Page from which the curated batches need to be retrieved
     * @param  integer $per_page Number of elements on a page
     * @return ArrayObject of CuratedBatch
     */
    public static function all($page = 1, $per_page = 10)
    {
        $curatedBatches = self::get(
            "curated_batches", 
            ['query' => ['page' => $page, 'per_page' => $per_page]]
        );

        $curatedBatchesArray = self::getArray($curatedBatches->getBody(), get_called_class());

        return new ArrayObject($curatedBatchesArray, $curatedBatches->getHeaders());
    }

    /**
     * Retrieve all the photos for a specific curated batch
     * Returns an ArrayObject that contains Photo objects.
     * 
     * @return ArrayObject of Photo
     */
    public function photos()
    {
        if (! isset($this->photos)) {
            $photos = self::get("curated_batches/{$this->id}/photos");

            $this->photos = [
                'body' => self::getArray($photos->getBody(), __NAMESPACE__.'\\Photo'),
                'headers' => $photos->getHeaders()
            ];
        }

        return new ArrayObject($this->photos['body'], $this->photos['headers']);
    }
}