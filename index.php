<?php


$inpufilename =  "./data.html";



class HTMLIterator implements \Iterator
{
 
    protected $filePointer = null;
 
    protected $currentElement = null;
 
    protected $rowCounter = null;
 
    public function __construct($file)
    {
        try {
            $this->filePointer = fopen($file, 'rb');
            $this->rowCounter = 0;
        } catch (\Exception $e) {
            throw new \Exception('The file "' . $file . '" cannot be read.');
        }
    }
 

    public function rewind(): void
    {
        $this->rowCounter = 0;
        rewind($this->filePointer);
    }
 

    public function current()
    {
        $this->currentElement =htmlspecialchars (fgets($this->filePointer, 4096));
       //var_dump(($this->currentElement  ));
        $this->rowCounter++;
 
        return ($this->currentElement);
    }
 

    public function key(): int
    {
        return $this->rowCounter;
    }
 

    public function next()
    {
        if (is_resource($this->filePointer)) {
            
            return !feof($this->filePointer);
        }
 
        return false;
    }
 

    public function valid(): bool
    {
        if (!$this->next()) {
            if (is_resource($this->filePointer)) {
                fclose($this->filePointer);
            }
 
            return false;
        }
 
        return true;
    }

    
}

function filter (string $str)
{
    
    if (
        str_starts_with (($str),htmlspecialchars('<meta name="keywords"'))
        || str_starts_with (($str),htmlspecialchars('<meta name="description"'))
    )
    {
        return false;
    }
    return true;
}


$iterator = new HTMLIterator($inpufilename);
$file = fopen('result.html', 'w'); 
foreach ($iterator as $key => $row) {

    if (filter($row))
    {
        fwrite($file, htmlspecialchars_decode($row)); 
    }
}
fclose($file);
echo 'Conversion complete. Check result.html';






?>