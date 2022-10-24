<div class="row">
    <div class="col-md-12">
        <div class="search-header">
            <section id="slider">
                <section class="search-box" style="padding: 10px;">
                    <div class="row">
                        {!! Form::open(['method' => 'get', 'route' => 'faq.general_faqs.search']) !!}
                        <div class="col-md-12 search-text">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="text-center">
                                    <br>
                                    <div class="form-group @if ($errors->has('title')) has-error @endif">
                                        {!! Form::input('text', 'search', $search_string, ['class' => 'form-control', 'placeholder' => 'Search for content', 'style' => 'text-align:center']) !!}
                                        @if ($errors->has('search')) <p class="help-block">{{ $errors->first('search') }}</p> @endif
                                        
                                        <button onclick="spin(this)" style="color: #8cc03c" class="btn-block btn btn-default"><i class="fa fa-search"></i> Search</button>
                
                                    </div>  
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </section>  
            </section> 
            <div class="strip">
                <h4 style="color:#FFFFFF;margin: 0 0 0px 0;">ONLINE, ANYWHERE, ANYTIME</h4>
            </div>
        </div>
    </div>
</div>