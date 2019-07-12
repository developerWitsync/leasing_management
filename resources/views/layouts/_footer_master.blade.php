<section id="bottom" class="bottom">
    <div class="container wow fadeInDown animated" data-wow-duration="1000ms" data-wow-delay="600ms" style="visibility: visible; animation-duration: 1000ms; animation-delay: 600ms; animation-name: fadeInDown;">
        <div class="row">
            <div class="col-md-3">
                <div class="widget">
                    <h3>About Us</h3>
                    <p>Building Intangible Excellence landscape in Education, Information Advisory and Business Support Function globally using advanced unique innovative technological features.</p>
                    <!-- <ul>
                        <li><a href="javascript:void(0);">About us</a></li>
                        <li><a href="javascript:void(0);">We are hiring</a></li>
                        <li><a href="javascript:void(0);">Meet the team</a></li>
                        <li><a href="javascript:void(0);">Copyright</a></li>
                        <li><a href="javascript:void(0);">Terms of use</a></li>
                        <li><a href="javascript:void(0);">Privacy policy</a></li>
                        <li><a href="javascript:void(0);">Contact us</a></li>
                    </ul> -->
                </div>
            </div>

            <div class="col-md-3 col-xs-12">
                <div class="widget">
                    <h3>Featured Links</h3>
                    <ul class="ft-link">
                        <li><a href="{{ route('information.about') }}"><i class="fa fa-angle-right"></i> About Us</a></li>
                        <li><a href="{{ route('information.services') }}"><i class="fa fa-angle-right"></i> Services</a></li>
                        <li><a href="{{ route('information.eLearning') }}"><i class="fa fa-angle-right"></i> E-Learning</a></li>
                        <li><a href="{{ route('contactus') }}"><i class="fa fa-angle-right"></i> Contact Us</a></li>
                    </ul>

                </div>
            </div>

            <div class="col-md-3 col-xs-12">
                <div class="widget">
                    <h3>Information</h3>
                    <ul class="">
                        <li><a href="mailto:info@witsync.co"><i class="fa fa-envelope-o"></i> info@witsync.co</a></li>
                    </ul>
                    <br>
                    {{--<h3>Newsletter</h3>--}}
                    {{--<form role="form">--}}
                        {{--<input type="email" class="form-control" autocomplete="off" placeholder="Enter your e-mail here">--}}
                        {{--<button type="submit" class="btn bt"><i class="fa fa-paper-plane-o"></i></button>--}}
                    {{--</form>--}}

                </div>
            </div>

            <div class="col-md-3">
                <div class="widget">
                    <h3>Get in Touch with Us</h3>

                    <div class="alert alert-success getintouchsuccess" style="display: none;">

                    </div>

                    <form id="getInTouchWithUs">
                        <input type="text" name="name" class="form-control" placeholder="Name">
                        <input type="email" name="email" class="form-control" placeholder="Business E-mail">
                        <input type="text" name="phone" class="form-control" placeholder="Mo. number">
                        <textarea class="form-control" name="comments" placeholder="Message"></textarea>
                        <div class="{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                        {!! app('captcha')->display([
                                              'data-theme' => 'light',
                                              'id' => 'rc-imageselect'
                                      ]) !!}

                        @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                        @endif
                            <span id = "captcha_error" style="color:red"></span>
                        </div>

                        <button type="submit" class="btn submit">SEND MESSAGE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<footer id="footer" class="midnight-blue text-center">
    <div class="container">
        <div class="row">
            <!-- <div class="col-md-6 text-left">
                <ul class="f-social">
                    <li><a href="javascript:void(0);"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="javascript:void(0);"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="javascript:void(0);"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="javascript:void(0);"><i class="fa fa-linkedin"></i></a></li>
                </ul>
            </div> -->
            <div class="col-md-6 text-left">&copy; Copyright @ {{ date('Y') }}. All Rights Reserved.</div>
            <div class="col-md-6 text-right">
                <ul class="">
                    @foreach(getInformationPage() as $page)
                        <li><a href="{{ route('information.index',['slug'=>$page->slug]) }}">{{ $page->title }}</a></li>
                    @endforeach
                    {{--<li><a href="javascript:void(0);">Code of Conduct</a></li>--}}
                    {{--<li><a href="javascript:void(0);">Privacy</a></li>--}}
                    {{--<li><a href="javascript:void(0);">Terms of Use</a></li>--}}
                </ul>
            </div>
            <!-- <hr> -->
        </div>
    </div>
</footer><!--/#footer-->

<div class="gototop" id="gototop" style=""><i class="fa fa-angle-up"></i></div>

