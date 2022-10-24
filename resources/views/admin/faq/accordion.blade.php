<?php
    $level++;
    $folder = $cat['model'];
?>
<div class="panel-group" id="accordion_{{ $cat['id'] }}" style="margin-bottom:0px;">
    <div class="panel panel-default">
        <div class="panel-heading" style="margin-top:5px;">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion_{{ $cat['id'] }}" href="#cat_{{ $cat['id'] }}" style="text-transform: capitalize">{{ $cat['name'] }}</a>
                @if ($level==2)
                    <!--<div class="label pull-right"><a href="{{ route('admin.categories.edit', $cat['id']) }}" class="btn btn-sm btn-info" style="margin-top:-8px;">Edit</a>
                    </div> -->
                    <div class="label pull-right"><a href="{{ route('faq.categories.unlink', $cat['id']) }}" class="btn btn-sm btn-danger" style="margin-top:-8px;">Unlink</a></div>
                @endif
                <div class="label label-no-plan pull-right">Categories: {{ count($cat['categories']) }}</div>
            </h4>
        </div>

        <div id="cat_{{ $cat['id'] }}" class="collapse">
            <div class="panel-body" style="padding:5px 15px;">
            @if(count($cat['categories']))
                @foreach($cat['categories'] as $category)
                    <?php
                        $cat = $category;
                    ?>
                    @include('admin.faq.accordion')
                @endforeach	 
            @else
                <h4 style="padding:10px 0px;">No category</h4>
            @endif 
            <hr>          
            </div>
        </div>
    </div>
</div>

