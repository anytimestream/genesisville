<?php
class Pagination {
    private $total;
    private $index;
    private $size;
    private $next;
    private $previous;
    private $url;
    private $maxPage;
    
    public function __construct() {
        $this->maxPage = 0;
    }

    public function setPages(){
        $this->next = $this->index;
        $this->previous = $this->index;
        if ($this->total % $this->size > 0) {
            $this->maxPage = ($this->total - ($this->total % $this->size)) / $this->size;
            $this->maxPage++;
        } else {
            $this->maxPage = $this->total / $this->size;
        }
        if ($this->index > 1) {
            $this->previous--;
        }
        if ($this->maxPage > 1 && $this->maxPage > $this->index) {
            $this->next++;
        }
    }
    
    public function getMaxPage(){
        return $this->maxPage;
    }
    
    public function getTotal(){
        return $this->total;
    }
    
    public function setTotal($total){
        $this->total = $total;
    }
    
    public function getIndex(){
        return $this->index;
    }
    
    public function setIndex($index){
        $this->index = $index;
    }
    
    public function getSize(){
        return $this->size;
    }
    
    public function setSize($size){
        $this->size = $size;
    }
    
    public function getNext(){
        return $this->next;
    }
    
    public function setNext($next){
        $this->next = $next;
    }
    
    public function getPrevious(){
        return $this->previous;
    }
     
    public function setPrevious($previous){
        $this->previous = $previous;
    }
    
    public function getUrl(){
        return $this->url;
    }
      
    public function setUrl($url){
        $this->url = $url;
    }
    
}
?>
