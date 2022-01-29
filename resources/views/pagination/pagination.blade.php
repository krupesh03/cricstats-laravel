@if( count($pagination) )
    @if( ($pagination['total'] / $pagination['per_page']) > 1 )
        <div class="pagination">
            @foreach( $pagination['links'] as $link )
                <a href="{{ isset(explode('?', $link['url'])[1]) ? '?' . explode('?', $link['url'])[1] : '' }}" class="{{ $link['active'] ? 'active' : 'inactive' }}">{{ html_entity_decode($link['label']) }}</a>
            @endforeach
        </div>
    @endif
@endif