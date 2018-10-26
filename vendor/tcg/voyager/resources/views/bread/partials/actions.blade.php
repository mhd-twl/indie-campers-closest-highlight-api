@php $action = new $action($dataType, $data); @endphp

@if ($action->shouldActionDisplayOnDataType())
    @can($action->getPolicy(), $data)
    	@if(isset($url_path) &&  $action->getTitle() == 'View' )
		<a href="{{ url($url_path."/show/".$data->id )}}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
            <i class="{{ $action->getIcon() }}"></i> <span class="hidden-xs hidden-sm">{{ $action->getTitle() }}</span>
        </a>
        @elseif(isset($url_path) &&  $action->getTitle() == 'Edit' )
        <a href="{{ url($url_path."/edit/".$data->id )}}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
	        <i class="{{ $action->getIcon() }}"></i> <span class="hidden-xs hidden-sm">{{ $action->getTitle() }}</span>
	    </a>
    	@else
        <a href="{{ $action->getRoute($dataType->name) }}" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!}>
            <i class="{{ $action->getIcon() }}"></i> <span class="hidden-xs hidden-sm">{{ $action->getTitle() }}</span>
        </a>
        @endif
    @endcan
@endif