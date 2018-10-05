<?php

namespace App\Http\Controllers;

use App\Choice;
use App\NeuralModels\Learn;
use App\NeuralModels\TextColor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        
        $textColor = $this->textColor();
        $isBlack = $textColor->input($r, $g, $b);

        return view('welcome', [
            'r' => $r,
            'g' => $g,
            'b' => $b,
            'color' => $isBlack ? 'black' : 'white',
        ]);
    }
    
    public function colors(Request $request) {
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        
        $textColor = $this->textColor();
        $isBlack = $textColor->input($r, $g, $b);
        $request->session()->put('model', $textColor->getModel());

        $colors = [];
        for ($r = 0; $r <= 255; $r+=25) {
            for ($g = 0; $g <= 255; $g+=25) {
                for ($b = 0; $b <= 255; $b+=25) {
                    $colors[] = [
                        'r' => $r,
                        'g' => $g,
                        'b' => $b,
                        'color' => $textColor->input($r, $g, $b) ? 'black' : 'white',
                    ];
                }
            }
        }

        return view('colors', ['colors' => $colors]);
    }

    public function create(Request $request) {
        $result = $request->validate([
            'color' => 'required|in:black,white',
            'r' => 'required|integer|min:0|max:255',
            'g' => 'required|integer|min:0|max:255',
            'b' => 'required|integer|min:0|max:255',
        ]);

        $textColor = $this->textColor();
        $textColor->learn((new Learn())->input($result['r'] / 255, $result['g'] / 255, $result['b'] / 255)->result($result['color'] === 'black'));

        $request->session()->put('model', $textColor->getModel());

        $choice = new Choice();
        $choice->red = $result['r'];
        $choice->green = $result['g'];
        $choice->blue = $result['b'];
        $choice->is_black = $result['color'] === 'black';
        $choice->save();

        return redirect(route('home'));
    }

    private function learn() {
        $textColor = TextColor::new();
        $choices = Choice::all();

        for ($i = 0; $i < 3000; $i++) {
            $choices->each(function($choice) use ($textColor) {
                $textColor->learn((new Learn())->input(
                    $choice->red,
                    $choice->green,
                    $choice->blue
                )->result($choice->is_black));
            });
        }

        return $textColor;
    }

    private function textColor() {
        if (session()->exists('model')) {
            return TextColor::new(session()->get('model'));
        }
        return $this->learn();
    }
}
