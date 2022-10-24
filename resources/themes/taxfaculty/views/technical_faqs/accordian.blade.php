<?php
    $level++;
    $folder = $category['model'];
?>

<div class="accordion" id="accordionExample" style="@if($level!=1) margin-left:10px; @endif">
    <div class="card" style="overflow: hidden;">
        <div class="card-header" id="headingOne">
            <button class="custom_filter btn btn-link panel-heading accordion-toggle {{ $level == 1 ? '' : 'collapsed in'}}" type="button" data-toggle="collapse" data-target="#{{ $category['id'] }}" aria-expanded="true" aria-controls="collapseOne" data-filter="{{ $category['slugs'] }}">
            {{ $category['name'] }} {{ count($category['question_ids'])?'('.count($category['question_ids']).')':'' }}
            </button>
        </div> 

        <div id="{{ $category['id'] }}" class="collapse {{ $level == 1 ? 'show in' : ''}}" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body">
                <ul class="nav mix-filter" style="text-transform: capitalize;">
                    @if(count($category['categories']))
                        @foreach($category['categories'] as $cat)
                            @if (!count($cat['categories']))
                                <li style="background-color: #ffffff!important; margin-top: 10px; margin-bottom: 10px">
                                    <a style="color: #8cc03c" id="faqs_tags" href="#" data-filter="{{ $cat['model']->slug }}" class="custom_filter" >
                                    {{ $cat['model']->title }} ({{$cat['model']->faqs->count()}})
                                    </a>
                                </li>
                            @else
                                <?php
                                    $category = $cat;
                                ?>
                                @include('technical_faqs.accordian')
                            @endif
                                
                        @endforeach
                    @else
                        <li data-filter="#" class="custom_filter active"><a href="#">No Tags</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>