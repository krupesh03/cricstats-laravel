<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;

class NewsController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
    }

    public function index() {

        $news = $this->apicallHelper->getNewsDataFromAPI();
        if( $news['success'] )
            $news = collect($news['data']);
        else
            $news = collect([]);

        return view('news/newslist', compact('news'));
    }
}
