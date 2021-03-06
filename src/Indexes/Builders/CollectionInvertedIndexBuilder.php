<?php
namespace TextAnalysis\Indexes\Builders;
use TextAnalysis\Interfaces\ICollection;
use TextAnalysis\Analysis\FreqDist;


/**
 * A really easy way to build an inverted index
 * @author Dan Cardin
 */
class CollectionInvertedIndexBuilder
{
    const FREQ = 'freq';
    const POSTINGS = 'postings';
    
    /**
     * The index that gets written out
     * @var type 
     */
    protected $index = array();
    
    /**
     * Build the index from the collection of documents
     * @param ICollection $collection 
     */
    public function __construct(ICollection $collection)
    {
        $this->buildIndex($collection);
    }
    
    /**
     * Builds the internal index data structure using the provided collection
     * @param ICollection $collection 
     */
    protected function buildIndex(ICollection $collection)
    {
        //first pass compute frequencies and all the terms in the collection
        foreach($collection as $id => $document) { 
            $freqDist = new FreqDist($document->getDocumentData());
            foreach($freqDist->getKeyValuesByFrequency() as $term => $freq) { 
                if(!isset($this->index[$term])) { 
                    $this->index[$term] = array(self::FREQ => 0, self::POSTINGS => array());
                }
                $this->index[$term][self::FREQ] += $freq;
                $this->index[$term][self::POSTINGS][] = $id;
            }
        }          
    }
    
    /**
     * Get the computed index
     * @return array
     */
    public function getIndex()
    {
        return $this->index;
    }
}

