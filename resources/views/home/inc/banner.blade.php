<?php $sty = '';
if(!empty($_GET['debu'])) {
  if($_GET['debu'] == 1)  {
    echo "home.inc.banner";
    $sty = ' style="border: 1px solid;" ';
  }
} ?>

<style>
    .lemiy {
    margin-top: 43px;
}




@media only screen and (max-width: 600px) {
 .lemiy h1 {
    font-size: 24px;
}
.lemiy h2 {
    font-size: 21px;
}
.lemiy h3 {
    font-size: 19px;
}
}
</style>
 <div class="ps-home ps-home--1">
            <section class="ps-section--banner">
                <div class="ps-section__overlay">
                    <div class="ps-section__loading"></div>
                </div>
                <div class="owl-carousel" data-owl-auto="true" data-owl-loop="true" data-owl-speed="3000"
                    data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1"
                    data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000"
                    data-owl-mousedrag="on">
                    <div class="ps-banner " style="background:#499344">
                        <div class="container container-initial">
                            <div class="ps-banner__block">
                                <div class="ps-banner__content wow fadeInLeft" data-wow-duration="1s"
                                    data-wow-delay=".4s"
                                    style="visibility: visible; animation-duration: 1s; animation-delay: 0.4s; animation-name: fadeInLeft;">
                                    <h2 class="ps-banner__title">India’s Fastest <br>Growing Online <br>B2B
                                        Pharmaceutical Marketplace</h2>
                                    <div class="ps-banner__desc">Post Your Requirement to Get Free Quote from Verified
                                        Suppliers.</div>
                                    <a class="bg-warning ps-banner__shop" target="blank" href="{{ lurl(trans('routes.contact')) }}">Contact Now</a>
                                </div>

                                <div class="ps-banner__thumnail wow fadeInRight" data-wow-duration="1s"
                                    data-wow-delay=".4s"
                                    style="visibility: visible; animation-duration: 1s; animation-delay: 0.4s; animation-name: fadeInRight;">
                                    <img class="ps-banner__round" src="/home/img/round2.png" alt="alt" /><img
                                        class="ps-banner__image" src="/home/img/promotion/slide1.png" alt="alt" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ps-banner" style="background:#bd9c1f;">
                        <div class="container container-initial">
                            <div class="ps-banner__block">
                                <div class="ps-banner__content">
                                    <h2 class="ps-banner__title">Looking For Pharmaceutical <br /> Companies for
                                        Business ?</h2>
                                    <div class="ps-banner__desc">Get Pharma PCD Companies , Pharma Franchise Company &
                                        Third Party Manufacturing .</div>
                                    <a class="bg-warning ps-banner__shop" target="blank" href="{{ lurl(trans('routes.contact')) }}">Contact Now</a>
                                </div>
                                <div class="ps-banner__thumnail"><img class="ps-banner__round" src="/home/img/round2.png"
                                        alt="alt" /><img class="ps-banner__image" src="/home/img/promotion/slide3.png"
                                        alt="alt" />
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
              </section>
        </div>


 <div class="container-fluid">
                <section class="ps-about--info mb-5 pb-5 pt-5 mt-5 wow zoomIn" data-wow-duration="1s" data-wow-delay=".4s" style="visibility: visible; animation-duration: 1s; animation-delay: 0.4s; animation-name: zoomIn;">
                    <div class="leage">
                <h2 class="ps-about__title">How it Works? </h2>
                <p class="ps-about__subtitle"> India's leading Pharmaceutical Franchise Companies Portal </p>
                <div class="ps-about__extent">
                    <div class="row m-0">
                        <div class="col-12 col-md-3 p-0">
                            <div class="ps-block--about  wow zoomIn" data-wow-duration="1s" data-wow-delay="1s" style="visibility: visible; animation-duration: 1s; animation-delay:1s; animation-name: zoomIn;">
                                <div class="ps-block__icon"><img src="/home/img/icon/1.png" alt=""></div>
                                <h4 class="ps-block__title">Post your requirements</h4>
                                </div>
                        </div>
                        <div class="col-12 col-md-3 p-0">
                            <div class="ps-block--about  wow zoomIn" data-wow-duration="1s" data-wow-delay="1.50s" style="visibility: visible; animation-duration: 1s; animation-delay: 1.50s; animation-name: zoomIn;">
                                <div class="ps-block__icon"><img src="/home/img/icon/2.png" alt=""></div>
                                <h4 class="ps-block__title">Your enquiry is verified</h4>
                                </div>
                        </div>
                        <div class="col-12 col-md-3 p-0">
                            <div class="ps-block--about  wow zoomIn" data-wow-duration="1s" data-wow-delay="2s" style="visibility: visible; animation-duration: 1s; animation-delay:2s; animation-name: zoomIn;">
                                <div class="ps-block__icon"><img src="/home/img/icon/3.png" alt=""></div>
                                <h4 class="ps-block__title">Your requirements are sent <br> to right suppliers</h4>
                                </div>
                        </div>
                        <div class="col-12 col-md-3 p-0">
                            <div class="ps-block--about  wow zoomIn" data-wow-duration="1s" data-wow-delay="2.50s" style="visibility: visible; animation-duration: 1s; animation-delay:2.50s; animation-name: zoomIn;">
                                <div class="ps-block__icon"><img src="/home/img/icon/4.png" alt=""></div>
                                <h4 class="ps-block__title">Suppliers will contact you</h4>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </section>
                </div>
