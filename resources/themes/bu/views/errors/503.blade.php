<html lang="en-us" class="no-js">
<head>
    <meta charset="utf-8">
    <title>Report Problems</title>
    <meta name="description" content="The description should optimally be between 150-160 characters.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Tiaan Theunissen">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/frontend/css/style-left-green.css">
</head>
<body>
<div class="container-fluid global-overlay">
    <div class="overlay skew-part"></div>
    <div class="side-overlay">
        <div class="left-part">
            <div class="content">
                <h1 class="count">
                    <div class="counter" data-count="503">0</div>
                    <span style="font-size: 60px">Be Right Back..</span>
                </h1>
                <h2>We are adding some new exciting features to our system and will be right back!</h2>
                <br>
                <div>
                    <a href="/" class="btn light-btn">Back Home!</a>
                    <a data-toggle="modal" data-target="#exampleModal"class="btn action-btn trigger">Report Problem</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="color: #333333" class="modal-title" id="exampleModalLabel">Report a new problem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['mehtod' => 'post', 'route' => 'new_problem', 'id' => 'contact', 'name' => 'contact-form', 'data-name' => 'Contact Form']) !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <input type="email" id="email" class="form form-control" placeholder="Your Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your Email'" name="email" data-name="Email Address" required>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-lg-12 no-padding">
                            <div class="form-group">
                                <textarea id="text-area" class="form textarea form-control" placeholder="Your feedback here... 20 characters Min." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your feedback here... 20 characters Min.'" name="body_message" data-name="Text Area" required></textarea>
                            </div>
                        </div>

                    </div>
                    <button type="submit" id="valid-form" class="action-btn trigger">Send my Message</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.bundle.min.js"></script>
<script>
    $('.counter').each(function() {
        var $this = $(this),
            countTo = $this.attr('data-count');

        $({ countNum: $this.text()}).animate({
            countNum: countTo
        },{
            duration: 1500,
            easing:'linear',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(this.countNum);
            }
        });
    });
</script>
</html>