<?php

namespace App\NeuralModels;

class Learn {
    // Input
    private $red;
    private $green;
    private $blue;
    
    // Result
    private $black;
    private $white;
    
    public function input($red, $green, $blue) {
        $this->red = $red / 255;
        $this->green = $green / 255;
        $this->blue = $blue / 255;
        return $this;
    }
    
    public function result($isBlack) {
        $this->black = $isBlack ? 1.0 : 0.0;
        $this->white = !$isBlack ? 1.0 : 0.0;
        return $this;
    }
    
    public function getInput() {
        return [$this->red, $this->green, $this->blue];
    }
    
    public function getResult() {
        return [$this->black, $this->white];
    }
}
