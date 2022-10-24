
<div id="overview" class="tab-pane fade in active">
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        {{-- {!! Form::open(['method' => 'get', 'route' => 'seoSearch']) !!}

            <input type="hidden" name="tableName" id="tableName"  value="{{ $name }}">
            <div class="form-group @if ($errors->has('event_name')) has-error @endif">
                {!! Form::label('name', 'Searching') !!}
                {!! Form::input('text', 'name', null, ['class' => 'form-control', 'placeholder' => 'Search']) !!}
                @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
            </div>

            <button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-search"></i> Search</button>
            {!! Form::close() !!} --}}
            
            <hr>
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Title</th>
                        <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($events as $event)
                    <?php 
                        $model = get_class($event);
                        $tableName = (new $model)->getTable(); 
                        $cname = getClasses($array,$event);
                    ?>
                    <tr>
                        <td>{{ $event->getName() }}</td>
                        <td>{{ $event->meta_description }}</td>
                        <td>{{ $event->checkMetaTitle() }}</td>
                        <td><a href="{{ route('seoEdit',['id' => $event->id, 'name' => $cname]) }}">Edit</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="text-center">
                @if(count($events))
                    {!! $events->render() !!}
                @endif
            </div>
        </div>
    </section>
</div>