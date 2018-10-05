<?php

namespace App\NeuralModels;

class TextColor
{
    private $model;
    
    public static function new(array $model = []) {
        if (count($model) === 0) {
            return new static();
        }
        return new static($model);
    }

    public function __construct(array $model = []) {
        if (count($model) === 0) {
            $this->buildNewModel();
            return;
        }
        $this->model = $model;
    }

    private function random() : float {
        return rand(0, 1000) / 1000;
    }

    private function buildNewModel() {
        $this->model = [
            [
                [[$this->random(),$this->random(), $this->random()], $this->random()],
                [[$this->random(),$this->random(), $this->random()], $this->random()],
                [[$this->random(),$this->random(), $this->random()], $this->random()],
            ],
            [
                [[$this->random(),$this->random(), $this->random()], $this->random()],
                [[$this->random(),$this->random(), $this->random()], $this->random()],
                [[$this->random(),$this->random(), $this->random()], $this->random()],
            ],
            [
                [[$this->random(),$this->random(),$this->random()]],
                [[$this->random(),$this->random(),$this->random()]],
            ],
        ];
    }
    
    public function learn(Learn $learn) {
        // for ($i = 0; $i < 105; $i++) {
            $this->learnProgram($learn);
        // }
    }
    
    // public function learn(Learn $learn) {
    //     $cont = true;
    //     $c = 0;
    //     while ($cont) {
    //         $c++;
    //         $this->learnProgram($learn);
    //         $input = $learn->getInput();
    //         $out = $this->inputRaw(
    //             $input[0],
    //             $input[1],
    //             $input[2]
    //         );
    //         $out = $out[count($out) - 1];
    //         $cont = false;
    //         foreach ($out as $i => $o) {
    //             if (($o - $learn->getResult()[$i]) > 0.1) {
    //                 $cont = true;
    //             }
    //         }
    //     }
    // }
    
    public function learnProgram(Learn $learn) {
        $learningRate = 0.03;
        $out = $this->inputRaw($learn->getInput()[0], $learn->getInput()[1], $learn->getInput()[2]);
        $isBlack = $out[count($out) - 1];
        $learnResult = $learn->getResult();
        $e_net = [
            0.5 * (($learnResult[0] - $isBlack[0])*($learnResult[0] - $isBlack[0])),
            0.5 * (($learnResult[1] - $isBlack[1])*($learnResult[1] - $isBlack[1])),
        ];
        // var_dump($learn->getResult(), $e_net);die;

        $totalError = [
            -($learnResult[0] - $isBlack[0]),
            -($learnResult[1] - $isBlack[1]),
        ];

        $difference = [
            -($learnResult[0] - $isBlack[0]),
            -($learnResult[1] - $isBlack[1]),
        ];

        $weightModifier = [];

        $m = $this->model;
        $nm = $this->model;

        // x = layer
        // y = neuron
        // z = weight

        $firstLayer = true;
        $e = [];
        $e_net = [];
        $out_net = [];
        $eTotal_outN = [];
        $eN_outN = [];
        $outN_netN = [];
        for ($x = count($m) - 1; $x >= 0; $x--) {
            array_push($e, []);
            array_push($e_net, []);
            array_push($out_net, []);
            array_push($eTotal_outN, []);
            // array_push($eN_outN, []);
            for ($y = count($m[$x]) - 1; $y >= 0; $y--) {
                $net = floatval(0);

                $out_net[$x][$y] = $out[$x+1][$y] * (1 - $out[$x+1][$y]);
                $outN_netN[$x][$y] = $out[$x+1][$y] * (1 - $out[$x+1][$y]);

                if ($firstLayer) {
                    $e[$x][$y] = 0.5 * (($learnResult[$y] - $out[$x][$y])*($learnResult[$y] - $out[$x][$y]));
                    $eTotal_outN[$x][$y] = -($learnResult[$y] - $out[$x+1][$y]);
                } else {
                    // Start $outN_netN

                    for ($y2 = 0; $y2 <= count($m[$x+1]) - 1; $y2++) {
                        $netN2_outN1[$x][$y][$x+1][$y2] = $m[$x+1][$y2][0][$y];

                        $eN_netN[$x+1][$y2] =
                            $eTotal_outN[$x+1][$y2] *
                                $outN_netN[$x+1][$y2];

                        $eN2_outN1[$x][$y][$x+1][$y2] = $eN_netN[$x+1][$y2] * $netN2_outN1[$x][$y][$x+1][$y2];
                    }
                    $eTotal_outN[$x][$y] = array_sum($eN2_outN1[$x][$y][$x+1]);
                    
                }

                // Highest Layer
                for ($z = count($m[$x][$y][0]) - 1; $z >= 0; $z--) {
                    // $net = $net + $m[$x][$y][0][$z] * $out[$x][$z];
                    // echo '<pre>';
                    // var_dump($learnResult, $x,$y,$z,$out,$m);die;
                    
                    
                    // $totalError = -($learnResult[$y]-$out[$x+1][$y]);
                    // $netInput = 1 * $out[$x][$z];

                    if ($firstLayer) {
                        $total = $eTotal_outN[$x][$y] * $out_net[$x][$y] * $out[$x][$z];
                        $nm[$x][$y][0][$z] = $m[$x][$y][0][$z] - $learningRate * $total;
                    } else {
                        $netN_w[$x][$y][$z] = $out[$x][$z];
                        $eTotal_w[$x][$y][$z] = $eTotal_outN[$x][$y] * $outN_netN[$x][$y] * $netN_w[$x][$y][$z];

                        // Update Weight
                        $nm[$x][$y][0][$z] = $m[$x][$y][0][$z] - $learningRate * $eTotal_w[$x][$y][$z];
                    }
                }
                // $out[$x+1][$y] = 1 / (1 + exp(-$net * 1));
            }
            $firstLayer = false;
        }

        // echo '<pre>';
        // var_dump($m[2], $nm[2]);die;

        // $change = $totalError * $difference * $weightModifier;
        $this->model = $nm;
    }
    
    public function getModel() {
        return $this->model;
    }

    public function inputRaw(...$input) {
        $out = [$input];
        $m = $this->model;

        // x = layer
        // y = neuron
        // z = weight

        for ($x = 0; $x <= count($m) - 1; $x++) {
            array_push($out, []);
            for ($y = 0; $y <= count($m[$x]) - 1; $y++) {
                $net = floatval(0);
                $mm = $m[$x];
                $mm = $mm[$y];
                $mm = $mm[0];
                for ($z = 0; $z <= count($mm) - 1; $z++) {
                    $net = $net + $mm[$z] * $out[$x][$z];
                }
                $out[$x+1][$y] = 1 / (1 + exp(-$net * 1));
            }
        }
        // echo '<pre>';
        // var_dump($out);
        return $out;
    }

    public function input(...$input) {
        $out = $this->inputRaw(
            $input[0] / 255,
            $input[1] / 255,
            $input[2] / 255
        );
        $out = $out[count($out) - 1];
        return $out[0] >= $out[1];
    }
}
