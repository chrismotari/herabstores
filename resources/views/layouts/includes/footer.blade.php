<!-- footer-area -->
<footer>
    <div class="footer-area gray-bg pt-80 pb-30">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="footer-widget mb-50">
                        <div class="footer-logo mb-25">
                            <a href="/"><img src="img/logo/logo.png" alt=""></a>
                        </div>
                        <div class="footer-contact-list">
                            <ul>
                                <li>
                                    <div class="icon"><i class="flaticon-place"></i></div>
                                    <p>Westfield Mbaazi, Lavington, Nairobi</p>
                                </li>
                                <li>
                                    <div class="icon"><i class="flaticon-telephone-1"></i></div>
                                    <h5 class="number"><a href="tel:+254789454242">+254 789 45 42 42</a></h5>
                                </li>
                                <li>
                                    <div class="icon"><i class="flaticon-mail"></i></div>
                                    <p>info@herabgroceries.com</p>
                                </li>
                                <li>
                                    <div class="icon"><i class="flaticon-wall-clock"></i></div>
                                    <p>Sun - Fri : 8:00 to 21:00</p>
                                </li>
                            </ul>
                        </div>
                        <div class="footer-social">
                            <ul>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                                <li><a href="#"><i class="fab fa-tiktok"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="footer-widget mb-50">
                        <div class="fw-title">
                            <h5 class="title">Customer Service</h5>
                        </div>
                        <div class="fw-link">
                            <ul>
                                <li><a href="#">Cart</a></li>
                                <li><a href="#">Payment Methods</a></li>
                                <li><a href="#">Track my Order</a></li>
                                <li><a href="#">My Orders and Returns</a></li>
                                <li><a href="#">Delivery</a></li>
                                <li><a href="#">Secure Shopping</a></li>
                                <li><a href="#">Legal Notice</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="footer-widget mb-50">
                        <div class="fw-title">
                            <h5 class="title">Useful Links</h5>
                        </div>
                        <div class="fw-link">
                            <ul>
                                <li><a href="#">Careers</a></li>
                                <li><a href="{{route('about.us')}}">About us</a></li>
                                <li><a href="{{route('terms.and.conditions')}}">Our Terms and Conditions</a></li>
                                <li><a href="{{route('contact.us')}}">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="footer-widget footer-box-widget mb-50">
                        <div class="f-download-wrap">
                            <div class="fw-title">
                                <h5 class="title">Download App</h5>
                            </div>
                            <div class="download-btns">
                                <a href="#"><img src="img/icon/g_play.png" alt=""></a>
                                <a href="#"><img src="img/icon/app_store.png" alt=""></a>
                            </div>
                        </div>
                        <div class="f-newsletter">
                            <div class="fw-title">
                                <h5 class="title">Newsletter</h5>
                            </div>
                            <form action="{{route('contacts.store')}}" method="post">
                                @csrf
                                <input type="email" placeholder="Email Address" name="email" required>
                                <button type="submit" ><i class="flaticon-send"></i></button>
                            </form>
                            <p>Be notified when wwe have new deals</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-wrap">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="copyright-text">
                        <p>Copyright &copy; {{now()->format('Y')}} <a href="https://saibadigital.tech" target="blank">Saiba Digital Solutions</a></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="payment-accepted text-center text-md-right">
                        <img src="img/images/payment_card.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-area-end -->