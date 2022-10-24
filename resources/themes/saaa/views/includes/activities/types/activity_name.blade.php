<li class="timeline-item success">
    <div class="margin-left-15">
	@if($activity->productable)	
        <div class="text-muted text-small">
            {{ $activity->created_at->diffForHumans() }}
        </div>
        <p>
            <?php
            $model = explode("\\",$activity->model);
			?>
			@if($activity->productable && strpos(get_class($activity->productable),'Invoice') > 0)
			<?php 
			$items = '';
			if(($activity->productable) && $activity->productable->items){

				$items = implode(",", $activity->productable->items->pluck('name')->toArray());
			}
			?>
			{{ $activity->user->first_name }} {{ $activity->action }} {{ end($model) }} {{ ($activity->productable)? ($activity->productable->items)? $items:"":"" }}
			@else	
			<?php 
				$name =($activity->productable)? ($activity->productable->name)?$activity->productable->name:"":"";
				if($name == ''){
				$name =($activity->productable)? ($activity->productable->title)?$activity->productable->title:"":"";
				}
				if($name == ''){
					$name =($activity->productable)? ((in_array("App\Traits\SEOTrait",class_uses($activity->productable)))
					&& @$activity->productable->getName())?@$activity->productable->getName():"":"";
				}
			?>				
            {{ $activity->user->first_name }} {{ $activity->action }} {{ end($model) }} {{ $name }}
			@endif
        </p>
		@endif
    </div>
</li>