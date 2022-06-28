@if( $news->count() )
    <div class="row px-3 pt-3 news-section">
        @foreach( $news as $n)
            <div class="col-md-6 news-list">
                {{ $n['title'] }}
                <div class="source-details"> 
                    <span> <b>Source:</b> <u>{{ ucfirst($n['source']) }}</u> </span>
                    @if( !empty($n['url']) )
                        <a href="{{ $n['url'] }}" target="_blank"> Read more... </a> 
                    @endif
                </div>
                <hr />
            </div>
        @endforeach
    </div>
@else
    <div class="error-msg"> There are no news at the moment. Please check back later! </div>
@endif