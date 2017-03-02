<?php
namespace Application\InputFilter;
use Traversable;
use Zend\InputFilter\CollectionInputFilter as ZendCollectionInputFilter;
class CollectionInputFilter extends ZendCollectionInputFilter
{    
    protected $uniqueFields;
    
    protected $message;
    
    const UNIQUE_MESSAGE = 'Each item must be unique within the collection';
	
    /**
     * @return the $message
     */
    public function getMessage()
    {
        return $this->message;
    }
    /**
     * @param field_type $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
    /**
     * @return the $uniqueFields
     */
    public function getUniqueFields()
    {
        return $this->uniqueFields;
    }
	/**
     * @param multitype:string  $uniqueFields
     */
    public function setUniqueFields($uniqueFields)
    {
        $this->uniqueFields = $uniqueFields;
    }
    
    public function isValid()
    {
        $valid = parent::isValid();
        // Check that any fields set to unique are unique
        if($this->uniqueFields)
        {
            // for each of the unique fields specified spin through the collection rows and grab the values of the elements specified as unique.
            foreach($this->uniqueFields as $k => $elementName)
            {
                $validationValues = array();
                foreach($this->collectionData as $rowKey => $rowValue)
                {
                    // Check if the row has a deleted element and if it is set to 1. If it is don't validate this row.
                    if(array_key_exists('deleted', $rowValue) && $rowValue['deleted'] == 1) continue;
                   
                    $validationValues[] = $rowValue[$elementName];
                }
                
                // Get only the unique values and then check if the count of unique values differs from the total count
                $uniqueValues = array_unique($validationValues);
                if(count($uniqueValues) < count($validationValues))
                {            
                    // The counts didn't match so now grab the row keys where the duplicate values were and set the element message to the element on that row
                    $duplicates = array_keys(array_diff_key($validationValues, $uniqueValues));
                    $valid = false;
                    $message = ($this->getMessage()) ? $this->getMessage() : $this::UNIQUE_MESSAGE;
                    foreach($duplicates as $duplicate)
                    {
                        $this->collectionInvalidInputs[$duplicate][$elementName] = array('unique' => $message);
                    }
                }
            }
            
            return $valid;
        }
    }
    public function getMessages()
    {
        $messages = array();
        if (is_array($this->getInvalidInput()) || $this->getInvalidInput() instanceof Traversable) {
            foreach ($this->getInvalidInput() as $key => $inputs) {
                foreach ($inputs as $name => $input) {
                    if(!is_string($input) && !is_array($input))
                    {
                        $messages[$key][$name] = $input->getMessages();                                                
                        continue;
                    }         
                    $messages[$key][$name] = $input;
                }
            }
        }
        return $messages;
    }
}
