@extends('layouts.app')
@section('content')
<main>

    <!-- map-area -->
    {{-- <div id="map-bg" data-background="img/bg/map.jpg"></div> --}}
    <!-- map-area-end -->

    <!-- contact-area -->
    <section class="contact-area pt-90 pb-90">
        <div class="container">
            <div class="container-inner-wrap">
                <div class="row justify-content-center justify-content-lg-between">
                    <div class="col-lg-6 col-md-8 order-2 order-lg-0">
                        <div class="contact-title mb-25">
                            {{-- <h5 class="sub-title">Contact Us</h5> --}}
                            <h2 class="title">Contact Us<span>.</span></h2>
                        </div>
                        <div class="contact-wrap-content">
                            <p>Submit your request below and one of our team members will reach out to you as soon as possible.</p>
                            <form action="{{route('contacts.store')}}" class="contact-form" method="post">
                                @csrf
                                <div class="form-grp">
                                    <label for="name">Your Name <span>*</span></label>
                                    <input type="text" id="name" placeholder="Jon Deo..." name="name" required>
                                </div>
                                <div class="form-grp">
                                    <label for="phone">Your Phone no. <span></span></label>
                                    <input type="text" id="phone" placeholder="+254 712 345 678" name="phone">
                                </div>
                                <div class="form-grp">
                                    <label for="email">Your Email <span>*</span></label>
                                    <input type="text" id="email" placeholder="info.example@.com" name="email" required>
                                </div>
                                <div class="form-grp">
                                    <label for="subject">Subject <span>*</span></label>
                                    <input type="email" id="subject" placeholder="Just to inquire" name="subject" required>
                                </div>
                                <div class="form-grp">
                                    <label for="message">Your Message <span>*</span></label>
                                    <textarea id="message" placeholder="Opinion..." name='message' required></textarea>
                                </div>
                                <div class="form-grp checkbox-grp">
                                    <input type="checkbox" id="checkbox" name="consented">
                                    <label for="checkbox">Don’t show your email address</label>
                                </div>
                                <button type="submit" class="btn rounded-btn">Send Now</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6 col-md-8">
                        <div class="contact-info-wrap">
                            <div class="contact-img">
                                <img src="img/images/contact_img.png" alt="">
                            </div>
                            <div class="contact-info-list">
                                <ul>
                                    <li>
                                        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                                        <div class="content">
                                            <p>Westfield Mbaazi, Lavington, Nairobi</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon"><i class="fas fa-phone-alt"></i></div>
                                        <div class="content">
                                            <p>+254 789 45 42 42</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon"><i class="fas fa-envelope-open"></i></div>
                                        <div class="content">
                                            <p>hello@herabgroceries.com</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="contact-social">
                                <ul>
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="bg-dark fa-brands fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-tiktok"></i></a></li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact-area-end -->

</main>
@endsection