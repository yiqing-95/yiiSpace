
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Help (Overview) - WikiNotes</title>
    <meta name="description" content="Collaborative note-sharing for the courses at McGill University. A free and open resource for students, by students.">
    <meta name="author" content="">

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->


    <link href="/static/css/bootstrap.css" rel="stylesheet" />


    <script src="/static/js/jquery.min.js"></script>
    <script src="/static/js/placeholder.min.js"></script>
    <script src="/static/js/jquery.fieldselection.min.js"></script>
    <script src="/static/js/page_course_shit.js"></script>
    <script src="/static/js/jquery.tablesorter.min.js"></script>
    <script src="/static/js/jquery.chosen.min.js"></script>
    <script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({
        tex2jax: {
        inlineMath: [  ['$', '$'] ],
        processEscapes: true,
        }
        });
    </script>

    <link rel="shortcut icon" href="/static/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/static/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/static/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/static/apple-touch-icon-precomposed.png">
</head>

<body>
<div id="wrap">
    <div id="main">
        <div class="topbar">
            <div class="topbar-inner">
                <div class="container">
                    <ul class="nav">
                        <li><a href="/">Home</a></li>
                        <li><a href="/courses">Courses</a></li>
                        <li><a href="/about">About</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/help">Help</a></li>
                        <li><a href="/contributing">Contributing</a></li>
                    </ul>
                    <div class="right-float">
                        <ul class="nav">
                            <li><form action="/search" method="get" id="search-form"><input type="text" name="query" class="medium" placeholder="Search ..." /></form></li>
                            <li><a href="/recent">Recent activity</a></li>
                            <li><a href="/register">Register</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="header">
            <div class="inner">
                <div class="container">
                    <a href="/"><img src="/static/img/logo.png" alt="wikinotes" width="300"></a>
                    <div id="login-bar">
                        <form method="post" action="/login">
                            <div style='display:none'><input type='hidden' name='csrfmiddlewaretoken' value='4017ef4ddc458fd17e1271aebeeef9a6' /></div>

                            <p>
                                <input class="medium" type="text" placeholder="Username" name="username" />
                                <input class="medium" type="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" name="password" />
                                <input type="submit" class="btn danger" value="Log in" name="login" />
                                <input type="hidden" value="/help" name="path" />
                            </p>
                            <div class="right-float">
                                <span><a href="/forgot-password">Forgot your password?</a>&nbsp;&nbsp;</span>
                            </div>
                            <label id="stay-logged-in">
                                <input type="checkbox" /> <span>Stay logged in</span>
                            </label>

                        </form>
                    </div>
                </div><!-- /container -->
            </div>
        </div>



        <div class="container">
            <section>
                <div class="row">
                    <div class="span12">
                        <div class="markdown">
                            <h1>Help <small>Overview</small></h1>
                            <hr />

                            <p>Although this section of the website is still under construction, you can check out our mostly up-to-date and fairly comprehensive <a href="/help/formatting">formatting docs</a> to learn how to use the markup on this site.</p>

                        </div>
                    </div>
                    <div class="span4">
                        <div id="right-bar">
                            <h4>Help</h4>
                            <ul class="unstyled">

                                <li>Overview</li>

                                <li><a href="/help/copyright">Copyright</a></li>

                                <li><a href="/help/formatting">Formatting</a></li>

                                <li><a href="/help/lexers">Lexers</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div><!-- /main -->
</div><!-- /wrap -->
<div id="footer">
    <div class="inner">
        <div class="container">
            <p class="right"><a href="#wrap">Back to top</a></p>
            <p>Powered by <a href="http://www.djangoproject.com">Django</a> and our own custom platform. Feel free to <a href="https://www.github.com/dellsystem/wikinotes">fork us</a> on github.</p>
        </div>
    </div>
</div>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-28456804-1']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
</body>
</html>
