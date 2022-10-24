@extends('admin.layouts.master')

@section('title', 'Frequently asked questions (FAQ)')
@section('description', 'Edit Question')

@section('styles')
    <link rel="stylesheet" href="/assets/admin/vendor/tagator/fm.tagator.jquery.min.css">
@stop

@section('content')
    <section>
        <br>
        <div class="container">
            {!! Form::model($question, ['method' => 'patch', 'route' => ['faq.questions_edit', $question->id]]) !!}
                @include('admin.faq.includes.questions')
                <button type="submit" class="btn btn-primary">Save Changes</button>
            {!! Form::close() !!}
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
    <script src="/assets/admin/vendor/tagator/fm.tagator.jquery.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            // $('.summernote').summernote({
            //     height: 300,                 // set editor height
            //     minHeight: null,             // set minimum height of editor
            //     maxHeight: null,             // set maximum height of editor
            //     focus: true,                  // set focus to editable area after initializing summernote
            //     toolbar: [
            //         // [groupName, [list of button]]
            //         ['style', ['bold', 'italic', 'underline', 'clear']],
            //         ['fontsize', ['14']],
            //         ['para', ['ul', 'ol', 'paragraph']],
            //     ]
            // });

            // Filter category on bases of faq type
            var categories = {!! $categories !!};
            $("#faq_type").on('change', function(){

                var html = '';
                if($(this).val()) {
                    for(i=0; i<categories.length; i++) {
                        if(categories[i].type==$(this).val()) {
                            html += '<option value="'+categories[i].id+'">'+categories[i].title+'</option>';
                        }
                    }
                }
                else {
                    for(i=0; i<categories.length; i++) {
                        html += '<option value="'+categories[i].id+'">'+categories[i].title+'</option>';
                    }
                }
                $('#categories_list').select2('val', '');
                $('#categories_list').html(html);
            })

            // Tags input
            $('#faq_tags').tagator();
        });

    </script>

    <script type="text/javascript">
        $('.select2').select2();
    </script>
@stop