<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;

class YoutubeSearch extends Component
{
    public $title;
    public $item;

    public function mount($item)
    {
        $this->item  = (array) $item;
        $this->title = $item->title;
    }

    public function render()
    {
        return view('livewire.youtube-search');
    }
}
